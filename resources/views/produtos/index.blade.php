@extends("layouts.app")
@section("content")
    <div class="container" style="margin-top: 30px;">
        <div class="row valign-wrapper">
            <div class="col s6">
                <h5>Produtos</h5>
            </div>
            <div class="col s6 right-align">
                <a href="{{ route("produtos.create") }}" class="btn waves-effect waves-light">
                    <i class="material-icons left">add</i>Novo Produto
                </a>
            </div>
        </div>

        <table class="striped responsive-table">
            <thead>
                <tr>
                    <th>Descrição</th>
                    <th>Categoria</th>
                    <th>Fornecedor</th>
                    <th>Preço Custo</th>
                    <th>Preço Venda</th>
                    <th>Estoque</th>
                    <th>Mín.</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($produtos as $produto)
                    <tr>
                        <td>{{ $produto->descricao }}</td>
                        <td>{{ $produto->categoria->descricao }}</td>
                        <td>{{ $produto->fornecedor->nome }}</td>
                        <td>R$ {{ number_format($produto->preco_custo, 2, ',', '.') }}</td>
                        <td>R$ {{ number_format($produto->preco_venda, 2, ',', '.') }}</td>
                        <td>
                            @if($produto->estoque_atual <= $produto->estoque_minimo)
                                <span style="color:#F09595">{{ $produto->estoque_atual }} ⚠</span>
                            @else
                                {{ $produto->estoque_atual }}
                            @endif
                        </td>
                        <td>{{ $produto->estoque_minimo }}</td>
                        <td>
                            <form action="{{ route("produtos.edit", $produto) }}" method="GET" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn-small waves-effect waves-light">
                                    <i class="material-icons">edit</i>
                                </button>
                            </form>
                            <form action="{{ route("produtos.destroy", $produto) }}" method="POST" style="display:inline;"
                                  onsubmit="return confirm('Deseja desativar este produto?')">
                                @csrf
                                @method("DELETE")
                                <button type="submit" class="btn-small red waves-effect waves-light">
                                    <i class="material-icons">delete</i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
