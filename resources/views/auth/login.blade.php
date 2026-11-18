{{-- TELA DE LOGIN --}}
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login — Equipe Móveis</title>
    <link rel="icon" href="{{ asset('images/logo-emp-icone-clara.png') }}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="login-page">

<div class="container">
    <div class="row" style="margin-top: 80px;">
        <div class="col s12 m6 offset-m3">
            <div class="card">
                <div class="card-content center-align">

                    <img src="{{ asset('images/logo-emp-icone-clara.png') }}" alt="Logo" style="max-width: 155px;">

                    {{-- route('login.store') gera a URL pelo NOME da rota. --}}
                    <form action="{{ route('login.store') }}" method="POST" novalidate>
                        {{-- @csrf: campo escondido com um token; o Laravel recusa POST sem ele. --}}
                        @csrf

                        <div class="input-field">
                            <i class="material-icons prefix">email</i>
                            <input id="email" type="email" name="email" value="{{ old('email') }}"
                                   class="@error('email') invalid @enderror" autofocus>
                            <label for="email" class="{{ old('email') ? 'active' : '' }}">E-mail</label>
                            @error('email')
                                <span class="helper-text red-text text-lighten-2 left-align">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="input-field">
                            <i class="material-icons prefix">lock</i>
                            <input id="password" type="password" name="password"
                                   class="@error('password') invalid @enderror">
                            <label for="password">Senha</label>
                            @error('password')
                                <span class="helper-text red-text text-lighten-2 left-align">{{ $message }}</span>
                            @enderror
                        </div>
                        <button class="btn waves-effect waves-light" type="submit">
                            Entrar <i class="material-icons right">login</i>
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>