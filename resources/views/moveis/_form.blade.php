{{--
    CAMPOS DO MÓVEL + LISTA DE MATERIAIS. Usado pelo create e pelo edit.
    old('campo', $movel->campo): se a validação falhar, mostra o que foi digitado;
    senão, o valor do banco (edição) ou o padrão (cadastro).
--}}
@php
    // Linhas de material para mostrar: as que a pessoa enviou (se a validação falhou)
    // ou uma linha vazia para começar.
    $linhas = old('itens', [['produto_id' => '', 'quantidade' => '']]);
    if (empty($linhas)) {
        $linhas = [['produto_id' => '', 'quantidade' => '']];
    }
    $editando = $movel->exists;
@endphp

<div class="row">
    <div class="input-field col s12 m8">
        <i class="material-icons prefix">chair</i>
        <input id="descricao" type="text" name="descricao" maxlength="60"
               value="{{ old('descricao', $movel->descricao) }}" class="@error('descricao') invalid @enderror">
        <label for="descricao" class="{{ old('descricao', $movel->descricao) ? 'active' : '' }}">Descrição *</label>
        @error('descricao') <span class="helper-text red-text text-lighten-2">{{ $message }}</span> @enderror
    </div>
</div>

<div class="row">
    <div class="input-field col s12 m4">
        <i class="material-icons prefix">category</i>
        <select id="categoria_id" name="categoria_id">
            <option value="" disabled @selected(! old('categoria_id', $movel->categoria_id))>Selecione</option>
            @foreach ($categorias as $categoria)
                <option value="{{ $categoria->id }}" @selected(old('categoria_id', $movel->categoria_id) == $categoria->id)>
                    {{ $categoria->descricao }}
                </option>
            @endforeach
        </select>
        <label for="categoria_id">Categoria *</label>
        @error('categoria_id') <span class="helper-text red-text text-lighten-2">{{ $message }}</span> @enderror
    </div>

    <div class="input-field col s12 m4">
        <i class="material-icons prefix">sell</i>
        <input id="preco_venda" type="number" step="0.01" min="0" name="preco_venda"
               value="{{ old('preco_venda', $movel->preco_venda) }}" class="@error('preco_venda') invalid @enderror">
        <label for="preco_venda" class="{{ old('preco_venda', $movel->preco_venda) !== null ? 'active' : '' }}">Preço de Venda *</label>
        @error('preco_venda') <span class="helper-text red-text text-lighten-2">{{ $message }}</span> @enderror
    </div>

    <div class="input-field col s12 m4">
        <i class="material-icons prefix">calendar_today</i>
        {{-- input type="date" precisa do formato AAAA-MM-DD. --}}
        <input id="data_cadastro" type="date" name="data_cadastro"
               value="{{ old('data_cadastro', $movel->data_cadastro?->format('Y-m-d')) }}"
               class="@error('data_cadastro') invalid @enderror">
        <label for="data_cadastro" class="active">Data *</label>
        @error('data_cadastro') <span class="helper-text red-text text-lighten-2">{{ $message }}</span> @enderror
    </div>
</div>

<div class="row">
    <div class="input-field col s12">
        <i class="material-icons prefix">notes</i>
        <input id="observacao" type="text" name="observacao" maxlength="255"
               value="{{ old('observacao', $movel->observacao) }}" class="@error('observacao') invalid @enderror">
        <label for="observacao" class="{{ old('observacao', $movel->observacao) ? 'active' : '' }}">Observação</label>
        @error('observacao') <span class="helper-text red-text text-lighten-2">{{ $message }}</span> @enderror
    </div>
</div>

{{-- Na edição, mostra o que o móvel já usa (só leitura). --}}
@if ($editando)
    <h6 style="color:#85B7EB; margin-top:10px;">
        <i class="material-icons tiny">build</i> Composição atual
    </h6>
    @if ($movel->itens->isNotEmpty())
        <table class="striped responsive-table" style="margin-bottom: 16px;">
            <thead><tr><th>Produto</th><th>Quantidade utilizada</th></tr></thead>
            <tbody>
                @foreach ($movel->itens as $item)
                    <tr>
                        <td>{{ $item->produto->descricao }}</td>
                        <td>{{ $item->quantidade }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="color:#85B7EB; font-size:12px;">Nenhum item na composição.</p>
    @endif
@endif

<h6 style="color:#85B7EB; margin-top:20px;">
    <i class="material-icons tiny">{{ $editando ? 'add_circle' : 'build' }}</i>
    {{ $editando ? 'Adicionar novos materiais' : 'Composição do Móvel *' }}
</h6>
<p style="font-size:12px; color:#85B7EB;">
    {{ $editando
        ? 'Opcional. Os materiais adicionados entram na composição e são descontados do estoque. Deixe a linha em branco para não adicionar nada.'
        : 'Adicione os materiais usados. O estoque será descontado automaticamente.' }}
</p>

{{-- Erros da lista de materiais (inclusive o "Estoque insuficiente" do controller). --}}
@if ($errors->has('itens') || $errors->has('itens.*'))
    <div class="card-panel red lighten-4" style="color:#c62828;">
        @foreach (array_unique(array_merge($errors->get('itens'), \Illuminate\Support\Arr::flatten($errors->get('itens.*')))) as $erro)
            <div><i class="material-icons tiny">error</i> {{ $erro }}</div>
        @endforeach
    </div>
@endif

<div id="itens-container">
    @foreach ($linhas as $i => $linha)
        <div class="row item-linha">
            <div class="input-field col s12 m8">
                {{-- itens[0][produto_id], itens[1][produto_id]... o PHP recebe como uma lista. --}}
                <select name="itens[{{ $i }}][produto_id]">
                    <option value="" @selected(empty($linha['produto_id']))>Selecione o produto</option>
                    @foreach ($produtos as $produto)
                        <option value="{{ $produto->id }}" @selected(($linha['produto_id'] ?? '') == $produto->id)>
                            {{ $produto->descricao }} (estoque: {{ $produto->estoque_atual }})
                        </option>
                    @endforeach
                </select>
                <label>Produto</label>
            </div>
            <div class="input-field col s10 m3">
                <input type="number" name="itens[{{ $i }}][quantidade]" min="1" placeholder="Qtd"
                       value="{{ $linha['quantidade'] ?? '' }}">
                <label class="active">Quantidade</label>
            </div>
            <div class="col s2 m1" style="margin-top: 20px;">
                <button type="button" class="btn-small red btn-remover-item" title="Remover linha">
                    <i class="material-icons">remove</i>
                </button>
            </div>
        </div>
    @endforeach
</div>

<button type="button" id="btn-add-item" class="btn waves-effect waves-light grey darken-1">
    <i class="material-icons left">add</i>Adicionar Produto
</button>

<div class="row" style="margin-top: 20px;">
    <div class="col s12">
        <button class="btn waves-effect waves-light" type="submit">
            <i class="material-icons left">save</i>Salvar
        </button>
        <a href="{{ route('moveis.index') }}" class="btn waves-effect waves-light grey darken-1" style="margin-left: 8px;">
            <i class="material-icons left">cancel</i>Cancelar
        </a>
    </div>
</div>

{{--
    JavaScript das linhas de material (vai para o @stack('scripts') do layout).
    Clona a primeira linha, limpa os valores e troca o índice: itens[0] -> itens[N].
    O índice precisa ser diferente em cada linha para o PHP não misturar os dados.
--}}
@push('scripts')
<script>
    (function () {
        var container = document.getElementById('itens-container');
        var proximo = container.querySelectorAll('.item-linha').length;

        document.getElementById('btn-add-item').addEventListener('click', function () {
            var modelo = container.querySelector('.item-linha');

            // O Materialize "embrulha" o <select> num componente próprio. Para clonar,
            // desmontamos o da linha modelo, clonamos o <select> puro e montamos de novo.
            var selectModelo = modelo.querySelector('select');
            M.FormSelect.getInstance(selectModelo)?.destroy();
            var nova = modelo.cloneNode(true);
            M.FormSelect.init(selectModelo);

            nova.querySelectorAll('select, input').forEach(function (campo) {
                campo.name = campo.name.replace(/itens\[\d+\]/, 'itens[' + proximo + ']');
                campo.value = '';
            });
            proximo++;

            container.appendChild(nova);
            M.FormSelect.init(nova.querySelector('select'));
        });

        container.addEventListener('click', function (e) {
            var botao = e.target.closest('.btn-remover-item');
            if (!botao) return;

            var linhas = container.querySelectorAll('.item-linha');
            var linha = botao.closest('.item-linha');
            if (linhas.length > 1) {
                linha.remove();
            } else {
                // Última linha: só limpa, para sempre sobrar uma para clonar.
                var select = linha.querySelector('select');
                select.value = '';
                M.FormSelect.init(select);
                linha.querySelector('input').value = '';
            }
        });
    })();
</script>
@endpush