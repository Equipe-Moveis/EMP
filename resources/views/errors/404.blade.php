{{--
    404 = NÃO ENCONTRADO. A URL não existe, ou o registro não existe
--}}
@extends('errors.layout')

@section('codigo', '404')
@section('icone', 'search_off')
@section('titulo', 'Página não encontrada')
@section('mensagem', 'A página ou o registro que você procurou não existe ou foi removido.')

@section('botoes')
    {{-- url()->previous() = página anterior. Se não houver, volta para a raiz. --}}
    <a href="{{ url()->previous() !== url()->current() ? url()->previous() : url('/') }}"
       class="btn grey darken-1 waves-effect waves-light">
        <i class="material-icons left">arrow_back</i>Voltar
    </a>
    {{-- auth()->check() = está logado? Logado vai ao dashboard; visitante, ao login. --}}
    @if (auth()->check())
        <a href="{{ route('dashboard') }}" class="btn waves-effect waves-light">
            <i class="material-icons left">dashboard</i>Dashboard
        </a>
    @else
        <a href="{{ route('login') }}" class="btn waves-effect waves-light">
            <i class="material-icons left">login</i>Fazer login
        </a>
    @endif
@endsection