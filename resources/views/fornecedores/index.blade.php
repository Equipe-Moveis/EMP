@extends("layouts.app")
@section("content")
<div class="container" style="margin-top: 30px;">
        <div class="row valign-wrapper">
            <div class="col s6">
                <h5>Fornecedores</h5>
            </div>
            <div class="col s6 right-align">
                <a href={{ route("fornecedores.create") }} class="btn waves-effect waves-light">
                    <i class="material-icons left">add</i>Novo Fornecedor
                </a>
            </div>
        </div>

        <table class="striped responsive-table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>CNPJ</th>
                    <th>Telefone</th>
                    <th>Cidade</th>
                    <th>UF</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($fornecedores as $fornecedor )
                    
                <tr>
                    <td>{{ $fornecedor->nome }}</td>
                    <td>{{ $fornecedor->cnpj }}</td>
                    <td>{{ $fornecedor->telefone }}</td>
                    <td>{{ $fornecedor->cidade }}</td>
                    <td>{{ $fornecedor->uf }}</td>
                    <td>
                        <form action={{ route("fornecedores.edit", $fornecedor) }}  style="display:inline;">
                            @csrf
                            <button type="submit" class="btn-small waves-effect waves-light">
                                <i class="material-icons">edit</i>
                            </button>
                        </form>
                        <form action={{ route("fornecedores.destroy", $fornecedor) }} method="POST" style="display:inline;"
                              onsubmit="return confirm('Deseja excluir este fornecedor?')">
                         @csrf
                         @method("delete")
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