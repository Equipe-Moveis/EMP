@extends("layouts.app")
@section("content")
 <div class="container" style="margin-top: 30px;">
        <h5>Novo Produto</h5>
        <form action="{{ route("produtos.store") }}" method="POST">
            @csrf
            <div class="row">
                <div class="input-field col s12 m8">
                    <i class="material-icons prefix">inventory_2</i>
                    <input id="descricao" type="text" name="descricao" required maxlength="60">
                    <label for="descricao">Descrição *</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12 m6">
                    <i class="material-icons prefix">category</i>
                    <select name="categoria_id" required>
                        <option value="" disabled selected>Selecione a categoria</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}">
                                {{ $categoria->descricao }}
                            </option>
                        @endforeach
                    </select>
                    <label>Categoria *</label>
                </div>
                <div class="input-field col s12 m6">
                    <i class="material-icons prefix">local_shipping</i>
                    <select name="fornecedor_id" required>
                        <option value="" disabled selected>Selecione o fornecedor</option>
                        @foreach($fornecedores as $fornecedor)
                            <option value="{{ $fornecedor->id }}">
                                {{ $fornecedor->nome }}
                            </option>
                        @endforeach
                    </select>
                    <label>Fornecedor *</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12 m3">
                    <i class="material-icons prefix">attach_money</i>
                    <input id="preco_custo" type="number" step="0.01" min="0"
                           name="preco_custo" required>
                    <label for="preco_custo">Preço de Custo *</label>
                </div>
                <div class="input-field col s12 m3">
                    <i class="material-icons prefix">sell</i>
                    <input id="preco_venda" type="number" step="0.01" min="0"
                           name="preco_venda" required>
                    <label for="preco_venda">Preço de Venda *</label>
                </div>
                <div class="input-field col s12 m3">
                    <i class="material-icons prefix">warehouse</i>
                    <input id="estoque_atual" type="number" min="0"
                           name="estoque_atual" required>
                    <label for="estoque_atual">Estoque Atual *</label>
                </div>
                <div class="input-field col s12 m3">
                    <i class="material-icons prefix">warning</i>
                    <input id="estoque_minimo" type="number" min="0"
                           name="estoque_minimo" required>
                    <label for="estoque_minimo">Estoque Mínimo *</label>
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