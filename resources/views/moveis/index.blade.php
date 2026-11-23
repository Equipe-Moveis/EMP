{{-- LISTA DE MÓVEIS (era o lstMovel.php) + formulário de filtro. --}}
@extends('layouts.app')

@section('title', 'Móveis')

@section('content')
    <div class="row valign-wrapper" style="margin-bottom: 0;">
        <div class="col s6"><h5>Móveis</h5></div>
        <div class="col s6 right-align">
            <a href="{{ route('moveis.create') }}" class="btn waves-effect waves-light">
                <i class="material-icons left">add</i>Novo Móvel
            </a>
        </div>
    </div>

    {{-- FILTRO: GET, para a busca ir na URL (?busca=...&categoria_id=...). --}}
    <div class="card">
        <div class="card-content">
            <form action="{{ route('moveis.index') }}" method="GET" class="row" style="margin-bottom: 0;">
                <div class="input-field col s12 m6">
                    <i class="material-icons prefix">search</i>
                    <input id="busca" type="text" name="busca" value="{{ $busca }}">
                    <label for="busca" class="{{ $busca ? 'active' : '' }}">Descrição</label>
                </div>
                <div class="input-field col s12 m3">
                    <select name="categoria_id" id="categoria_id">
                        <option value="">Todas</option>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->id }}" @selected($categoriaId == $categoria->id)>
                                {{ $categoria->descricao }}
                            </option>
                        @endforeach
                    </select>
                    <label for="categoria_id">Categoria</label>
                </div>
                <div class="col s12 m3" style="margin-top: 20px;">
                    <button type="submit" class="btn waves-effect waves-light" title="Filtrar">
                        <i class="material-icons">filter_list</i>
                    </button>
                    <a href="{{ route('moveis.index') }}" class="btn grey darken-1 waves-effect waves-light" title="Limpar filtro">
                        <i class="material-icons">clear</i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <table class="striped responsive-table">
        <thead>
            <tr>
                <th>Descrição</th>
                <th>Categoria</th>
                <th>Preço de Venda</th>
                <th>Data</th>
                <th>Materiais</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($moveis as $movel)
                <tr>
                    <td>{{ $movel->descricao }}</td>
                    <td>{{ $movel->categoria->descricao }}</td>
                    <td>R$ {{ number_format($movel->preco_venda, 2, ',', '.') }}</td>
                    <td>{{ $movel->data_cadastro->format('d/m/Y') }}</td>
                    {{-- itens_count vem do withCount('itens') do controller. --}}
                    <td>{{ $movel->itens_count }}</td>
                    <td>
                        <a href="{{ route('moveis.show', $movel) }}" class="btn-small blue waves-effect waves-light" title="Detalhes">
                            <i class="material-icons">visibility</i>
                        </a>
                        <a href="{{ route('moveis.edit', $movel) }}" class="btn-small waves-effect waves-light" title="Editar">
                            <i class="material-icons">edit</i>
                        </a>
                        <form action="{{ route('moveis.destroy', $movel) }}" method="POST" style="display:inline;"
                              onsubmit="return confirm('Deseja excluir este móvel? O estoque dos materiais NÃO volta.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-small red waves-effect waves-light" title="Excluir">
                                <i class="material-icons">delete</i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="center-align">Nenhum móvel encontrado.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="center-align" style="margin: 20px 0;">
        @if ($moveis->hasPages())
            <ul class="pagination">
                <li class="{{ $moveis->onFirstPage() ? 'disabled' : 'waves-effect' }}">
                    <a href="{{ $moveis->previousPageUrl() ?? '#!' }}"><i class="material-icons">chevron_left</i></a>
                </li>
                @foreach ($moveis->getUrlRange(1, $moveis->lastPage()) as $pagina => $url)
                    <li class="{{ $pagina == $moveis->currentPage() ? 'active' : 'waves-effect' }}">
                        <a href="{{ $url }}">{{ $pagina }}</a>
                    </li>
                @endforeach
                <li class="{{ $moveis->hasMorePages() ? 'waves-effect' : 'disabled' }}">
                    <a href="{{ $moveis->nextPageUrl() ?? '#!' }}"><i class="material-icons">chevron_right</i></a>
                </li>
            </ul>
        @endif
        <p class="grey-text">{{ $moveis->total() }} móvel(is) encontrado(s)</p>
    </div>
@endsection