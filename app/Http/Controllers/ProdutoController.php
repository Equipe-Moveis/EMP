<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Fornecedor;
use App\Models\Produto;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function index(): View
    {
        $produtos = Produto::ativos()
            ->with(['categoria', 'fornecedor'])
            ->orderBy('descricao')
            ->get();

        return view('produtos.index', compact('produtos'));
    }

    public function create(): View
    {
        $fornecedores = Fornecedor::orderBy('nome')->get();
        $categorias = Categoria::orderBy('descricao')->get();

        return view('produtos.create', compact('fornecedores', 'categorias'));
    }

    public function store(Request $request): RedirectResponse
    {
        $dados = $request->validate([
            'descricao' => 'required|string|min:3|max:60',
            'categoria_id' => 'required|exists:categorias,id',
            'fornecedor_id' => 'required|exists:fornecedores,id',
            'preco_custo' => 'required|numeric|min:0|max:99999999.99',
            'preco_venda' => 'required|numeric|min:0|max:99999999.99',
            'estoque_atual' => 'required|integer|min:0',
            'estoque_minimo' => 'required|integer|min:0',
        ]);

        Produto::create($dados);

        return redirect()->route('produtos.index')->with('success', 'Produto criado com sucesso!');
    }

    public function edit(Produto $produto): View
    {
        $fornecedores = Fornecedor::orderBy('nome')->get();
        $categorias = Categoria::orderBy('descricao')->get();

        return view('produtos.edit', compact('produto', 'fornecedores', 'categorias'));
    }

    public function update(Request $request, Produto $produto): RedirectResponse
    {
        $dados = $request->validate([
            'descricao' => 'required|string|min:3|max:60',
            'categoria_id' => 'required|exists:categorias,id',
            'fornecedor_id' => 'required|exists:fornecedores,id',
            'preco_custo' => 'required|numeric|min:0|max:99999999.99',
            'preco_venda' => 'required|numeric|min:0|max:99999999.99',
            'estoque_atual' => 'required|integer|min:0',
            'estoque_minimo' => 'required|integer|min:0',
        ]);

        $produto->update($dados);

        return redirect()->route('produtos.index')->with('success', 'Produto editado com sucesso!');
    }

    public function destroy(Produto $produto): RedirectResponse
    {
        // Exclusão lógica: preserva o histórico de movimentações e os itens de móvel vinculados.
        $produto->update(['ativo' => false]);

        return redirect()->route('produtos.index')->with('success', 'Produto desativado com sucesso!');
    }
}
