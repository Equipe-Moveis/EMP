<?php

namespace App\Http\Controllers;

use App\Http\Requests\MovelRequest;
use App\Models\Categoria;
use App\Models\Movel;
use App\Models\MovelItem;
use App\Models\Movimentacao;
use App\Models\Produto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

// CRUD de móveis. Cada método substitui um arquivo do PHP puro:
//   index -> lstMovel.php      create/store -> frmInsMovel.php / opInsMovel.php
//   show  -> detMovel.php      edit/update  -> frmEdtMovel.php / opEdtMovel.php
//   destroy -> opRemMovel.php
//
// Regra de negócio (a mesma do sistema antigo): ao montar um móvel, os materiais
// usados saem do estoque. Aqui, além de baixar o estoque, cada saída fica registrada
// na tabela de movimento (movimentacoes), com o responsável e o saldo depois da saída.
class MovelController extends Controller
{
    // Lista com FORMULÁRIO DE FILTRO (busca por descrição + categoria).
    public function index(Request $request): View
    {
        $busca       = $request->input('busca');
        $categoriaId = $request->input('categoria_id');

        $moveis = Movel::query()
            // with(): já traz a categoria junto, numa consulta só (evita uma consulta por linha).
            // withCount('itens'): cria $movel->itens_count com quantos materiais ele tem.
            ->with('categoria')
            ->withCount('itens')
            ->when($busca, fn ($q, $busca) => $q->where('descricao', 'like', "%{$busca}%"))
            ->when($categoriaId, fn ($q, $id) => $q->where('categoria_id', $id))
            ->orderBy('descricao')
            ->paginate(10)
            ->withQueryString();

        $categorias = Categoria::orderBy('descricao')->get();

        return view('moveis.index', compact('moveis', 'categorias', 'busca', 'categoriaId'));
    }

    public function create(): View
    {
        return view('moveis.create', [
            'movel'      => new Movel(['data_cadastro' => now()]),
            'categorias' => Categoria::orderBy('descricao')->get(),
            'produtos'   => Produto::ativos()->orderBy('descricao')->get(),
        ]);
    }

    public function store(MovelRequest $request): RedirectResponse
    {
        $dados = $request->validated();

        // DB::transaction = "tudo ou nada". Se faltar estoque no 3º material,
        // o móvel, os itens e as baixas dos 2 primeiros são DESFEITOS.
        // No PHP puro isso não existia: o móvel ficava gravado pela metade.
        DB::transaction(function () use ($dados, $request) {
            $movel = Movel::create($dados);

            $this->adicionarItens($movel, $dados['itens'], $request->user()->id);
        });

        return redirect()->route('moveis.index')
            ->with('success', 'Móvel cadastrado e estoque atualizado com sucesso!');
    }

    // Detalhes do móvel + composição (era o detMovel.php).
    public function show(Movel $movel): View
    {
        // load(): carrega as relações num objeto que já existe.
        // 'itens.produto' = os itens e, de cada item, o produto.
        $movel->load(['categoria', 'itens.produto']);

        return view('moveis.show', compact('movel'));
    }

    public function edit(Movel $movel): View
    {
        $movel->load('itens.produto');

        return view('moveis.edit', [
            'movel'      => $movel,
            'categorias' => Categoria::orderBy('descricao')->get(),
            'produtos'   => Produto::ativos()->orderBy('descricao')->get(),
        ]);
    }

    // Edição: altera os dados do móvel e, se vierem, ADICIONA novos materiais
    // (igual ao sistema antigo: o que já foi usado não volta para o estoque).
    public function update(MovelRequest $request, Movel $movel): RedirectResponse
    {
        $dados = $request->validated();

        DB::transaction(function () use ($movel, $dados, $request) {
            $movel->update($dados);

            if (! empty($dados['itens'])) {
                $this->adicionarItens($movel, $dados['itens'], $request->user()->id);
            }
        });

        return redirect()->route('moveis.index')
            ->with('success', 'Móvel atualizado com sucesso!');
    }

    public function destroy(Movel $movel): RedirectResponse
    {
        // Os itens (movel_itens) são apagados pelo próprio banco (cascadeOnDelete na migration).
        // As movimentações continuam no histórico, só com movel_id = NULL (nullOnDelete).
        // O estoque NÃO volta: os materiais já foram usados na fabricação.
        $movel->delete();

        return redirect()->route('moveis.index')
            ->with('success', 'Móvel excluído com sucesso!');
    }

    // ------------------------------------------------------------------
    // Grava os materiais de um móvel, baixa o estoque e registra a saída.
    // Usado pelo store e pelo update. Deve rodar DENTRO de DB::transaction.
    // (Era o BaixaEstoque + INSERT INTO movel_item do DAL antigo.)
    // ------------------------------------------------------------------
    private function adicionarItens(Movel $movel, array $itens, int $userId): void
    {
        foreach ($itens as $item) {
            // lockForUpdate(): "tranca" a linha do produto até o fim da transação.
            // Se duas pessoas montarem móveis ao mesmo tempo com o mesmo produto,
            // a segunda espera a primeira terminar, e o estoque não fica errado.
            $produto = Produto::lockForUpdate()->findOrFail($item['produto_id']);
            $quantidade = (int) $item['quantidade'];

            if ($produto->estoque_atual < $quantidade) {
                // Lançar a exceção faz o DB::transaction desfazer tudo, e o Laravel
                // volta para o formulário mostrando a mensagem no campo "itens".
                throw ValidationException::withMessages([
                    'itens' => "Estoque insuficiente de \"{$produto->descricao}\": "
                             . "disponível {$produto->estoque_atual}, pedido {$quantidade}.",
                ]);
            }

            // decrement() faz "UPDATE produtos SET estoque_atual = estoque_atual - X".
            $produto->decrement('estoque_atual', $quantidade);

            // Se o produto já faz parte do móvel (edição), soma na mesma linha.
            // firstOrNew: busca o item; se não existir, cria um objeto novo (ainda não salvo).
            $movelItem = MovelItem::firstOrNew([
                'movel_id'   => $movel->id,
                'produto_id' => $produto->id,
            ]);
            $movelItem->quantidade = ($movelItem->quantidade ?? 0) + $quantidade;
            $movelItem->save();

            // TABELA DE MOVIMENTO: cada material usado vira uma SAÍDA no histórico.
            Movimentacao::create([
                'produto_id'   => $produto->id,
                'user_id'      => $userId,
                'movel_id'     => $movel->id,
                'tipo'         => Movimentacao::SAIDA,
                'quantidade'   => $quantidade,
                'estoque_apos' => $produto->estoque_atual,
                'observacao'   => "Usado no móvel: {$movel->descricao}",
            ]);
        }
    }
}