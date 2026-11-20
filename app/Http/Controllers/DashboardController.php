<?php

namespace App\Http\Controllers;

use App\Models\Movel;
use App\Models\Movimentacao;
use App\Models\Produto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Painel inicial: números do estoque, produtos para repor,
     * últimos móveis cadastrados e últimas movimentações.
     */
    public function index(): View
    {
        // ---------- Cards do topo ----------
        // Os scopes ativos() e estoqueBaixo() já existem no model Produto.
        $produtosAtivos = Produto::ativos()->count();
        $fornecedoresDosAtivos = Produto::ativos()->distinct()->count('fornecedor_id');
        $abaixoDoMinimo = Produto::ativos()->estoqueBaixo()->count();
        $semEstoque = Produto::ativos()->where('estoque_atual', 0)->count();
        $valorEmEstoque = (float) Produto::ativos()->sum(DB::raw('estoque_atual * preco_custo'));
        $moveisCadastrados = Movel::count();

        // ---------- Repor estoque ----------
        // Do mais crítico para o menos crítico: quanto menor atual/mínimo, mais urgente.
        // O "* 1.0" evita divisão inteira (12 / 30 daria 0) e o NULLIF evita dividir por zero.
        $produtosParaRepor = Produto::ativos()
            ->estoqueBaixo()
            ->with('fornecedor')
            ->orderByRaw('estoque_atual * 1.0 / NULLIF(estoque_minimo, 0)')
            ->orderBy('descricao')
            ->take(5)
            ->get();

        // ---------- Últimos móveis ----------
        $ultimosMoveis = Movel::with('categoria')
            ->latest('data_cadastro')
            ->latest('id')
            ->take(3)
            ->get();

        // A lista já vem do mais novo para o mais antigo: o primeiro é o último cadastrado.
        $ultimoMovelEm = $ultimosMoveis->first()?->data_cadastro;

        // ---------- Últimas movimentações ----------
        $ultimasMovimentacoes = Movimentacao::with(['produto', 'user'])
            ->latest()
            ->latest('id')
            ->take(5)
            ->get();

        // "Quarta-feira, 18 de novembro de 2026"
        $hoje = Str::ucfirst(now()->locale('pt_BR')->translatedFormat('l, j \d\e F \d\e Y'));

        return view('dashboard', compact(
            'produtosAtivos',
            'fornecedoresDosAtivos',
            'abaixoDoMinimo',
            'semEstoque',
            'valorEmEstoque',
            'moveisCadastrados',
            'ultimoMovelEm',
            'produtosParaRepor',
            'ultimosMoveis',
            'ultimasMovimentacoes',
            'hoje',
        ));
    }
}
