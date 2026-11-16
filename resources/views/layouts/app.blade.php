<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Equipe Móveis') — Equipe Móveis</title>

    <link rel="icon" href="{{ asset('/Gemini_logo.png') }}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    @include('partials.menu')

    <main class="container" style="margin-top: 30px;">
        @yield('content')
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="{{ asset('js/init.js') }}"></script>

    @if (session('sucesso'))
        <script>M.toast({html: @json(session('sucesso')), classes: 'green darken-1'});</script>
    @endif
    @if (session('erro'))
        <script>M.toast({html: @json(session('erro')), classes: 'red darken-2'});</script>
    @endif

    @stack('scripts')
</body>
</html>