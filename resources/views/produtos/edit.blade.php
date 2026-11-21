@extends("layouts.app")
@section("content")
    <div class="container" style="margin-top: 30px;">
        <h5>Editar Produto</h5>

        <form action="{{ route("produtos.update", $produto) }}" method="POST">
            @csrf
            @method("PUT")

            <div class="row">
                <div class="input-field col s12 m8">
                    <i class="material-icons prefix">inventory_2</i>
                    <input id="descricao" type="text" name="descricao" required maxlength="60"
                           value="{{ $produto->descricao }}">
                    <label for="descricao" class="active">Descrição *</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12 m6">
                    <i class="material-icons prefix">category</i>
                    <select name="categoria_id" required>
                        <option value="" disabled>Selecione a categoria</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}" @selected($categoria->id == $produto->categoria_id)>
                                {{ $categoria->descricao }}
                            </option>
                        @endforeach
                    </select>
                    <label class="active">Categoria *</label>
                </div>
                <div class="input-field col s12 m6">
                    <i class="material-icons prefix">local_shipping</i>
                    <select name="fornecedor_id" required>
                        <option value="" disabled>Selecione o fornecedor</option>
                        @foreach($fornecedores as $fornecedor)
                            <option value="{{ $fornecedor->id }}" @selected($fornecedor->id == $produto->fornecedor_id)>
                                {{ $fornecedor->nome }}
                            </option>
                        @endforeach
                    </select>
                    <label class="active">Fornecedor *</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12 m3">
                    <i class="material-icons prefix">attach_money</i>
                    <input id="preco_custo" type="number" step="0.01" min="0"
                           name="preco_custo" required
                           value="{{ $produto->preco_custo }}">
                    <label for="preco_custo" class="active">Preço de Custo *</label>
                </div>
                <div class="input-field col s12 m3">
                    <i class="material-icons prefix">sell</i>
                    <input id="preco_venda" type="number" step="0.01" min="0"
                           name="preco_venda" required
                           value="{{ $produto->preco_venda }}">
                    <label for="preco_venda" class="active">Preço de Venda *</label>
                </div>
                <div class="input-field col s12 m3">
                    <i class="material-icons prefix">warehouse</i>
                    <input id="estoque_atual" type="number" min="0"
                           name="estoque_atual" required
                           value="{{ $produto->estoque_atual }}">
                    <label for="estoque_atual" class="active">Estoque Atual *</label>
                </div>
                <div class="input-field col s12 m3">
                    <i class="material-icons prefix">warning</i>
                    <input id="estoque_minimo" type="number" min="0"
                           name="estoque_minimo" required
                           value="{{ $produto->estoque_minimo }}">
                    <label for="estoque_minimo" class="active">Estoque Mínimo *</label>
                </div>
            </div>
            <div class="row">
                <div class="col s12">
                    <button class="btn waves-effect waves-light" type="submit">
                        <i class="material-icons left">save</i>Salvar
                    </button>
                    <a href="{{ route("produtos.index") }}" class="btn waves-effect waves-light grey darken-1" style="margin-left: 8px;">
                        <i class="material-icons left">cancel</i>Cancelar
                    </a>
                </div>
            </div>
        </form>
    </div>
@endsection
