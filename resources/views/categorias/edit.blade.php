@extends("layouts.app")
@section("content")
  <div class="container" style="margin-top: 30px;">
        <h5>Editar Categoria</h5>

        <form action={{ route("categorias.update", $categoria) }} method="POST">
            @csrf
            @method("put")
            
            <input type="hidden" name="id" value="{{ $categoria->id }}">

            <div class="row">
                <div class="input-field col s12 m8">
                    <i class="material-icons prefix">category</i>
                    <input id="descricao" type="text" name="descricao" required maxlength="60"
                           value="{{ $categoria->descricao }}">
                    <label for="descricao" class="active">Descrição *</label>
                </div>
            </div>
            <div class="row">
                <div class="col s12">
                    <button class="btn waves-effect waves-light" type="submit">
                        <i class="material-icons left">save</i>Salvar
                    </button>
                    <a href="{{ route('categorias.index') }}" class="btn waves-effect waves-light grey darken-1" style="margin-left: 8px;">
                        <i class="material-icons left">cancel</i>Cancelar
                    </a>
                </div>
            </div>
        </form>
    </div>
@endsection