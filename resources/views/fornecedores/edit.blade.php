@extends("layouts.app")
@section("content")
<div class="container" style="margin-top: 30px;">
        <h5>Editar Fornecedor</h5>

        <form action={{ route("fornecedores.update", $fornecedor)}} method="POST">
            @csrf
            @method("PUT")
            <div class="row">
                <div class="input-field col s12 m6">
                    <i class="material-icons prefix">business</i>
                    <input id="nome" type="text" name="nome" required maxlength="35"
                           value="{{ $fornecedor->nome }}">
                    <label for="nome" class="active">Nome *</label>
                </div>
                <div class="input-field col s12 m6">
                    <i class="material-icons prefix">badge</i>
                    <input id="cnpj" type="text" name="cnpj" required maxlength="18"
                           value="{{ $fornecedor->cnpj }}">
                    <label for="cnpj" class="active">CNPJ *</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12 m6">
                    <i class="material-icons prefix">phone</i>
                    <input id="telefone" type="text" name="telefone" required maxlength="15"
                           value="{{ $fornecedor->telefone }}">
                    <label for="telefone" class="active">Telefone *</label>
                </div>
                <div class="input-field col s12 m6">
                    <i class="material-icons prefix">email</i>
                    <input id="email" type="email" name="email" required maxlength="50"
                           value="{{ $fornecedor->email }}">
                    <label for="email" class="active">E-mail *</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12 m8">
                    <i class="material-icons prefix">location_city</i>
                    <input id="cidade" type="text" name="cidade" required maxlength="35"
                           value="{{ $fornecedor->cidade }}">
                    <label for="cidade" class="active">Cidade *</label>
                </div>
                <div class="input-field col s12 m4">
                    <i class="material-icons prefix">map</i>
                    <select name="uf" id="uf" required>
                        <option value="" disabled>Selecione</option>
                        <?php
                            $estados = ['AC','AL','AM','BA','CE','DF','ES','GO','MA',
                                        'MG','MS','MT','PA','PB','PE','PI','PR','RJ',
                                        'RN','RO','RR','RS','SC','SE','SP','TO'];
                            foreach ($estados as $estado) {
                                $sel = ($estado == $fornecedor->uf) ? 'selected' : '';
                                echo "<option value='$estado' $sel>$estado</option>";
                            }
                        ?>
                    </select>
                    <label for="uf" class="active">Estado *</label>
                </div>
            </div>
            <div class="row">
                <div class="col s12">
                    <button class="btn waves-effect waves-light" type="submit">
                        <i class="material-icons left">save</i>Salvar
                    </button>
                    <a href={{ route("fornecedores.index") }} class="btn waves-effect waves-light grey darken-1" style="margin-left: 8px;">
                        <i class="material-icons left">cancel</i>Cancelar
                    </a>
                </div>
            </div>
        </form>
    </div>
@endsection