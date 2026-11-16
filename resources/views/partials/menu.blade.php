@php
    $links = [
        ['rota' => 'produtos.index',      'icone' => 'inventory_2',    'texto' => 'Produtos'],
        ['rota' => 'fornecedores.index',  'icone' => 'local_shipping', 'texto' => 'Fornecedores'],
        ['rota' => 'categorias.index',    'icone' => 'category',       'texto' => 'Categorias'],
        ['rota' => 'moveis.index',        'icone' => 'chair',          'texto' => 'Móveis'],
        ['rota' => 'movimentacoes.index', 'icone' => 'swap_vert',      'texto' => 'Movimentações'],
        ['rota' => 'relatorios.index',    'icone' => 'assessment',     'texto' => 'Relatórios'],
    ];
@endphp

<nav id="menu-principal">
    <div class="nav-wrapper">
        <a href="{{ route('dashboard') }}" class="brand-logo left">
            <img src="{{ asset('/Gemini_logo.png') }}" alt="Logo" style="margin: 8px 0 0 15px; max-height: 48px;">
        </a>

        <a href="#" data-target="menu-mobile" class="sidenav-trigger right">
            <i class="material-icons">menu</i>
        </a>

        <ul class="right hide-on-med-and-down">
            @foreach ($links as $link)
                @if (Route::has($link['rota']))
                    <li><a href="{{ route($link['rota']) }}">
                        <i class="material-icons left">{{ $link['icone'] }}</i>{{ $link['texto'] }}
                    </a></li>
                @endif
            @endforeach

            @can('gerenciar-usuarios')
                <li><a href="{{ route('usuarios.index') }}">
                    <i class="material-icons left">people</i>Usuários
                </a></li>
            @endcan

            <li>
                {{-- Logout é POST com token CSRF, não um link comum. --}}
                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn-flat white-text" style="height:64px;">
                     <a><i class="material-icons left">exit_to_app</i></a>
                    </button>
                </form>
            </li>
        </ul>
    </div>
</nav>

<ul class="sidenav" id="menu-mobile">
    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
    @foreach ($links as $link)
        @if (Route::has($link['rota']))
            <li><a href="{{ route($link['rota']) }}">{{ $link['texto'] }}</a></li>
        @endif
    @endforeach
    @can('gerenciar-usuarios')
        <li><a href="{{ route('usuarios.index') }}">Usuários</a></li>
    @endcan
    <li class="divider"></li>
    <li>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-flat" style="width:100%; text-align:left; padding-left:32px;">Sair</button>
        </form>
    </li>
</ul>