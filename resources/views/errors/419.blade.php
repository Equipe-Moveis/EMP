{{--
    419 = PÁGINA EXPIRADA. O token CSRF (@csrf) do formulário venceu.
--}}
@extends('errors.layout')

@section('codigo', '419')
@section('icone', 'timer_off')
@section('titulo', 'Sessão expirada')
@section('mensagem', 'Por segurança, sua sessão expirou porque a página ficou muito tempo aberta. Recarregue a página e tente de novo.')

@section('botoes')
    @if (auth()->check())
        <a href="{{ url()->previous() }}" class="btn waves-effect waves-light">
            <i class="material-icons left">refresh</i>Voltar e tentar de novo
        </a>
    @else
        <a href="{{ route('login') }}" class="btn waves-effect waves-light">
            <i class="material-icons left">login</i>Fazer login novamente
        </a>
    @endif
@endsection