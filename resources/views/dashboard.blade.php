@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="dash">

    <header class="dash-header">
        <h1 class="dash-title">Dashboard</h1>
        <p class="dash-date">{{ $hoje }}</p>
    </header>

    {{-- ---------- Cards do topo ---------- --}}
    <section class="dash-kpis" aria-label="Resumo do estoque">
        <div class="dash-kpi">
            <p class="dash-kpi-label">Produtos ativos</p>
            <p class="dash-kpi-value">{{ $produtosAtivos }}</p>
            <p class="dash-kpi-meta">
                de {{ $fornecedoresDosAtivos }} {{ $fornecedoresDosAtivos === 1 ? 'fornecedor' : 'fornecedores' }}
            </p>
        </div>

        <div class="dash-kpi">
            <p class="dash-kpi-label">Abaixo do mínimo</p>
            <p @class(['dash-kpi-value', 'dash-alerta' => $abaixoDoMinimo > 0])>{{ $abaixoDoMinimo }}</p>
            <p class="dash-kpi-meta">{{ $semEstoque }} sem estoque</p>
        </div>

        <div class="dash-kpi">
            <p class="dash-kpi-label">Móveis cadastrados</p>
            <p class="dash-kpi-value">{{ $moveisCadastrados }}</p>
            <p class="dash-kpi-meta">
                @if ($ultimoMovelEm)
                    Último em {{ $ultimoMovelEm->format('d/m/Y') }}
                @else
                    Nenhum cadastrado
                @endif
            </p>
        </div>

        <div class="dash-kpi">
            <p class="dash-kpi-label">Valor em estoque</p>
            <p class="dash-kpi-value">R$ {{ number_format($valorEmEstoque, 2, ',', '.') }}</p>
            <p class="dash-kpi-meta">Pelo preço de custo</p>
        </div>
    </section>

    <div class="dash-grid">

        {{-- ---------- Repor estoque ---------- --}}
        <section class="dash-card" aria-labelledby="titulo-repor">
            <div class="dash-card-header">
                <h2 id="titulo-repor" class="dash-card-title">Repor estoque</h2>
                {{-- O link só aparece quando o CRUD de produtos existir. --}}
                @if (Route::has('produtos.index'))
                    <a href="{{ route('produtos.index') }}" class="dash-card-link">Ver produtos</a>
                @endif
            </div>

            <div class="dash-list-head">
                <span>Produto</span>
                <span>Atual / mínimo</span>
            </div>

            @forelse ($produtosParaRepor as $produto)
                @php
                    // Barra: quanto do estoque mínimo ainda existe (100% = chegou no mínimo).
                    $percentual = $produto->estoque_minimo > 0
                        ? min(100, round($produto->estoque_atual * 100 / $produto->estoque_minimo, 1))
                        : 0;
                @endphp
                <div class="dash-row">
                    <div class="dash-row-main">
                        <p class="dash-row-title">{{ $produto->descricao }}</p>
                        <p class="dash-row-sub">{{ $produto->fornecedor->nome }}</p>
                    </div>
                    <div class="dash-estoque">
                        <p class="dash-estoque-numeros">
                            <span @class(['dash-estoque-atual', 'dash-alerta' => $produto->estoque_atual == 0])>{{ $produto->estoque_atual }}</span>
                            <span class="dash-estoque-minimo">/ {{ $produto->estoque_minimo }}</span>
                        </p>
                        <div class="dash-bar">
                            <div class="dash-bar-fill" style="width: {{ $percentual }}%"></div>
                        </div>
                    </div>
                </div>
            @empty
                <p class="dash-empty">Nenhum produto abaixo do mínimo.</p>
            @endforelse
        </section>

        {{-- ---------- Últimos móveis ---------- --}}
        <section class="dash-card" aria-labelledby="titulo-moveis">
            <div class="dash-card-header">
                <h2 id="titulo-moveis" class="dash-card-title">Últimos móveis</h2>
                {{-- O link só aparece quando o CRUD de móveis existir. --}}
                @if (Route::has('moveis.index'))
                    <a href="{{ route('moveis.index') }}" class="dash-card-link">Ver móveis</a>
                @endif
            </div>

            <div class="dash-moveis">
                @forelse ($ultimosMoveis as $movel)
                    <div class="dash-row">
                        <div class="dash-row-main">
                            <p class="dash-row-title">{{ $movel->descricao }}</p>
                            <p class="dash-row-sub">{{ $movel->categoria->descricao }}, {{ $movel->data_cadastro->format('d/m/Y') }}</p>
                        </div>
                        <p class="dash-preco">R$ {{ number_format($movel->preco_venda, 2, ',', '.') }}</p>
                    </div>
                @empty
                    <p class="dash-empty">Nenhum móvel cadastrado ainda.</p>
                @endforelse
            </div>
        </section>
    </div>

    {{-- ---------- Últimas movimentações ---------- --}}
    <section class="dash-card" aria-labelledby="titulo-movimentacoes">
        <div class="dash-card-header">
            <h2 id="titulo-movimentacoes" class="dash-card-title">Últimas movimentações</h2>
        </div>

        @if ($ultimasMovimentacoes->isEmpty())
            <p class="dash-empty">Nenhuma movimentação registrada ainda.</p>
        @else
            <div class="dash-table-wrap">
                <table class="dash-table">
                    <thead>
                        <tr>
                            <th class="col-data">Data</th>
                            <th class="col-produto">Produto</th>
                            <th class="col-tipo">Tipo</th>
                            <th class="col-qtd">Qtd.</th>
                            <th class="col-saldo">Saldo</th>
                            <th class="col-responsavel">Responsável</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ultimasMovimentacoes as $movimentacao)
                            @php $entrada = $movimentacao->tipo === \App\Models\Movimentacao::ENTRADA; @endphp
                            <tr>
                                <td class="col-data">{{ $movimentacao->created_at->format('d/m H:i') }}</td>
                                <td class="col-produto">{{ $movimentacao->produto->descricao }}</td>
                                <td @class(['col-tipo', 'dash-entrada' => $entrada])>{{ $entrada ? 'Entrada' : 'Saída' }}</td>
                                <td @class(['col-qtd', 'dash-entrada' => $entrada])>{{ $entrada ? '+' : '' }}{{ $movimentacao->quantidade }}</td>
                                <td class="col-saldo">{{ $movimentacao->estoque_apos }}</td>
                                <td class="col-responsavel">{{ $movimentacao->user->name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>

</div>
@endsection
