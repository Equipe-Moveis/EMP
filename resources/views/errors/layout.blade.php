<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('codigo') — @yield('titulo') | Equipe Móveis</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
<div class="container">
    <div class="row" style="margin-top: 80px;">
        <div class="col s12 m8 offset-m2">
            <div class="card center-align">
                <div class="card-content">
                    <img src="{{ asset('images/logo-emp-icone-clara.png') }}" alt="Equipe Móveis" style="height: 90px;">

                    <h1 style="margin: 10px 0 0; font-weight: 700;">@yield('codigo')</h1>
                    <i class="material-icons large">@yield('icone')</i>
                    <h5>@yield('titulo')</h5>
                    <p style="margin: 15px 0 25px;">@yield('mensagem')</p>

                    {{-- Cada página coloca aqui os botões que fazem sentido para aquele erro. --}}
                    @yield('botoes')
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>