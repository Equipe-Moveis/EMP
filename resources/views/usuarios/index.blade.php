{{-- LISTA DE USUÁRIOS (era o lstUsuario.php) + formulário de filtro. --}}
@extends('layouts.app')

@section('title', 'Usuários')

@section('content')
    <div class="row valign-wrapper" style="margin-bottom: 0;">
        <div class="col s6"><h5>Usuários</h5></div>
        <div class="col s6 right-align">
            <a href="{{ route('usuarios.create') }}" class="btn waves-effect waves-light">
                <i class="material-icons left">add</i>Novo Usuário
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-content">
            <form action="{{ route('usuarios.index') }}" method="GET" class="row" style="margin-bottom: 0;">
                <div class="input-field col s12 m6">
                    <i class="material-icons prefix">search</i>
                    <input id="busca" type="text" name="busca" value="{{ $busca }}">
                    <label for="busca" class="{{ $busca ? 'active' : '' }}">Nome ou e-mail</label>
                </div>
                <div class="input-field col s12 m3">
                    <select name="perfil" id="perfil">
                        <option value="">Todos</option>
                        <option value="admin" @selected($perfil === 'admin')>Administrador</option>
                        <option value="funcionario" @selected($perfil === 'funcionario')>Funcionário</option>
                    </select>
                    <label for="perfil">Perfil</label>
                </div>
                <div class="col s12 m3" style="margin-top: 20px;">
                    <button type="submit" class="btn waves-effect waves-light">
                        <i class="material-icons">filter_list</i>
                    </button>
                    <a href="{{ route('usuarios.index') }}" class="btn grey darken-1 waves-effect waves-light" title="Limpar filtro">
                        <i class="material-icons">clear</i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <table class="striped responsive-table">
        <thead>
            <tr>
                <th>Nome</th>
                <th>E-mail</th>
                <th>Perfil</th>
                <th>Cadastrado em</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($usuarios as $u)
                <tr>
                    <td>{{ $u->name }}</td>
                    <td>{{ $u->email }}</td>
                    <td>
                        @if ($u->isAdmin())
                            <span class="new badge blue" data-badge-caption="">Administrador</span>
                        @else
                            <span class="new badge grey" data-badge-caption="">Funcionário</span>
                        @endif
                    </td>
                    <td>{{ $u->created_at?->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('usuarios.edit', $u) }}" class="btn-small waves-effect waves-light" title="Editar">
                            <i class="material-icons">edit</i>
                        </a>
                        <form action="{{ route('usuarios.destroy', $u) }}" method="POST" style="display:inline;"
                              onsubmit="return confirm('Deseja excluir este usuário?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-small red waves-effect waves-light" title="Excluir"
                                    @disabled($u->is(auth()->user()))>
                                <i class="material-icons">delete</i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="center-align">Nenhum usuário encontrado.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- Paginação: só aparece se houver mais de 10 usuários. --}}
    <div class="center-align" style="margin: 20px 0;">
        @if ($usuarios->hasPages())
            <ul class="pagination">
                <li class="{{ $usuarios->onFirstPage() ? 'disabled' : 'waves-effect' }}">
                    <a href="{{ $usuarios->previousPageUrl() ?? '#!' }}"><i class="material-icons">chevron_left</i></a>
                </li>
                @foreach ($usuarios->getUrlRange(1, $usuarios->lastPage()) as $pagina => $url)
                    <li class="{{ $pagina == $usuarios->currentPage() ? 'active' : 'waves-effect' }}">
                        <a href="{{ $url }}">{{ $pagina }}</a>
                    </li>
                @endforeach
                <li class="{{ $usuarios->hasMorePages() ? 'waves-effect' : 'disabled' }}">
                    <a href="{{ $usuarios->nextPageUrl() ?? '#!' }}"><i class="material-icons">chevron_right</i></a>
                </li>
            </ul>
        @endif
        <p class="white-text">{{ $usuarios->total() }} usuário(s) encontrado(s)</p>
    </div>
@endsection