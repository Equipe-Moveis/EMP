@extends("layouts.app")
@section("content")
 <div class="container" style="margin-top: 30px;">
        <h5>Nova Categoria</h5>

        <form action="{{ route('categorias.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="input-field col s12 m8">
                    <i class="material-icons prefix">category</i>
                    <input id="descricao" type="text" name="descricao" required minlength="3" maxlength="60">
                    <label for="descricao">Descrição *</label>
                </div>
            </div>
            <div class="row">
                <div class="col s12">
                    <button class="btn waves-effect waves-light" type="submit">
                        <i class="material-icons left">save</i>Salvar
                    </button>
                    <a href="lstCategoria.php" class="btn waves-effect waves-light grey darken-1" style="margin-left: 8px;">
                        <i class="material-icons left">cancel</i>Cancelar
                    </a>
                </div>
            </div>
        </form>
    </div>
@endsection