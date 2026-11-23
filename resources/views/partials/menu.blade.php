@php
use App\Http\Controllers\FornecedorController;
use App\Http\Controllers\MovelController;
use App\Http\Controllers\ProdutoController;
Use App\Http\Controllers\UsuarioController;
Use App\Http\Controllers\DashboardController;

    $links = [
    ['rota' => 'dashboard',           'icone' => 'dashboard',      'texto' => 'Dashboard'],
    ['rota' => 'categorias.index',    'icone' => 'label',          'texto' => 'Categorias'],
    ['rota' => 'moveis.index',        'icone' => 'chair',          'texto' => 'Móveis'],
    ['rota' => 'produtos.index',      'icone' => 'inventory_2',    'texto' => 'Produtos'],
    ['rota' => 'fornecedores.index',  'icone' => 'local_shipping', 'texto' => 'Fornecedores'],
    ['rota' => 'usuarios.index',      'icone' => 'people',         'texto' => 'Usuários',    'admin' => true],
    ['rota' => 'logout',              'icone' => 'logout',         'texto' => 'Sair'],
    ];
    
@endphp

<nav id="menu-principal">
    <div class="nav-wrapper">
        <a href="{{ route('dashboard') }}" class="brand-logo left" style="display:flex; align-items:center; margin-left:15px;">
            <img src="{{ asset('images/logo-emp-icone-clara.png') }}" alt="Equipe Móveis" style="height:46px;">
        </a>

        <a href="#" data-target="menu-mobile" class="sidenav-trigger right">
            <i class="material-icons">menu</i>
        </a>

        <ul class="right hide-on-med-and-down">
            {{-- Links de todos --}}
                        @foreach ($links as $link)
                @if (Route::has($link['rota']) && (empty($link['admin']) || auth()->user()->can('gerenciar-usuarios')))
                    @if ($link['rota'] === 'logout')
                        <li>
                            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn-flat btn-sair">
                                    <i class="material-icons left">{{ $link['icone'] }}</i>{{ $link['texto'] }}
                                </button>
                            </form>
                        </li>
                    @else
                        <li class="{{ request()->routeIs(str_replace('.index', '.*', $link['rota'])) ? 'active' : '' }}">
                            <a href="{{ route($link['rota']) }}">
                                <i class="material-icons left">{{ $link['icone'] }}</i>{{ $link['texto'] }}
                            </a>
                        </li>
                    @endif
                @endif
            @endforeach

            @can('gerenciar-usuarios')
                <li class="{{ request()->routeIs('usuarios.*') ? 'active' : '' }}">
                    <a href="{{ route('usuarios.index') }}">
                    </a>
                </li>
            @endcan
        </ul>
    </div>
</nav>

{{-- Menu do celular: mesma regra. --}}
<ul class="sidenav" id="menu-mobile">
    <li class="center-align" style="padding:16px 0;">
        <img src="{{ asset('images/logo-emp-clara.png') }}" alt="Equipe Móveis" style="width:70%;">
    </li>
        @foreach ($links as $link)
        @if (Route::has($link['rota']) && (empty($link['admin']) || auth()->user()->can('gerenciar-usuarios')))
            @if ($link['rota'] === 'logout')
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-flat" style="width:100%; text-align:left; padding-left:32px; color:#85b7eb;">
                            <i class="material-icons left">{{ $link['icone'] }}</i>{{ $link['texto'] }}
                        </button>
                    </form>
                </li>
            @else
                <li><a href="{{ route($link['rota']) }}"><i class="material-icons">{{ $link['icone'] }}</i>{{ $link['texto'] }}</a></li>
            @endif
        @endif
    @endforeach
    <li class="divider"></li>
</ul>