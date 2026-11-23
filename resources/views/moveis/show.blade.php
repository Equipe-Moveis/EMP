{{-- DETALHES DO MÓVEL (era o detMovel.php). --}}
@extends('layouts.app')

@section('title', 'Detalhes do Móvel')

@section('content')
    <h5>Detalhes do Móvel</h5>

    <div class="card">
        <div class="card-content">
            <p><strong>Descrição:</strong> {{ $movel->descricao }}</p>
            <p><strong>Categoria:</strong> {{ $movel->categoria->descricao }}</p>
            <p><strong>Preço de Venda:</strong> R$ {{ number_format($movel->preco_venda, 2, ',', '.') }}</p>
            <p><strong>Data de Cadastro:</strong> {{ $movel->data_cadastro->format('d/m/Y') }}</p>
            <p><strong>Observação:</strong> {{ $movel->observacao ?: '—' }}</p>
        </div>
    </div>

    <h6 style="color:#85B7EB; margin-top:20px;">Composição</h6>
    <table class="striped responsive-table">
        <thead>
            <tr>
                <th>Produto</th>
                <th>Quantidade utilizada</th>
                <th>Custo unitário</th>
                <th>Custo total</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($movel->itens as $item)
                <tr>
                    <td>{{ $item->produto->descricao }}</td>
                    <td>{{ $item->quantidade }}</td>
                    <td>R$ {{ number_format($item->produto->preco_custo, 2, ',', '.') }}</td>
                    <td>R$ {{ number_format($item->produto->preco_custo * $item->quantidade, 2, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="center-align">Nenhum material cadastrado.</td></tr>
            @endforelse
        </tbody>
        @if ($movel->itens->isNotEmpty())
            {{-- sum() com função: soma custo × quantidade de cada item. --}}
            <tfoot>
                <tr>
                    <th colspan="3" class="right-align">Custo total dos materiais:</th>
                    <th>R$ {{ number_format($movel->itens->sum(fn ($i) => $i->produto->preco_custo * $i->quantidade), 2, ',', '.') }}</th>
                </tr>
            </tfoot>
        @endif
    </table>

    <div style="margin-top: 20px;">
        <a href="{{ route('moveis.index') }}" class="btn waves-effect waves-light grey darken-1">
            <i class="material-icons left">arrow_back</i>Voltar
        </a>
        <a href="{{ route('moveis.edit', $movel) }}" class="btn waves-effect waves-light" style="margin-left: 8px;">
            <i class="material-icons left">edit</i>Editar
        </a>
    </div>
@endsection