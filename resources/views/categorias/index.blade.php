@extends('layouts.app')

@section('content')
<div class="container" style="margin-top: 30px;">
        <div class="row valign-wrapper">
            <div class="col s6">
                <h5>Categorias</h5>
            </div>
            <div class="col s6 right-align">
                <a href="{{ route("categorias.create") }}" class="btn waves-effect waves-light">
                    <i class="material-icons left">add</i>Nova Categoria
                </a>
            </div>
        </div>

        <table class="striped responsive-table">
            <thead>
                <tr>
                    <th>Descrição</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                
                @foreach($categorias as $categoria)
                <tr>
                    <td>{{ $categoria->descricao }}</td>
                    <td>
                        <form action="{{ route("categorias.edit", $categoria) }}" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn-small waves-effect waves-light">
                                <i class="material-icons">edit</i>
                            </button>
                        </form>
                        <form action="{{ route("categorias.destroy", $categoria) }}" method="POST" style="display:inline;">
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