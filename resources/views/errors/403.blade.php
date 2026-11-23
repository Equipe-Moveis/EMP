{{--
    403 = ACESSO NEGADO. A pessoa está logada, mas não tem permissão.
--}}
@extends('errors.layout')

@section('codigo', '403')
@section('icone', 'block')
@section('titulo', 'Acesso negado')
@section('mensagem', 'Você não tem permissão para acessar esta página. Se acha que isso é um erro, fale com o administrador do sistema.')

@section('botoes')
    <a href="{{ route('dashboard') }}" class="btn waves-effect waves-light">
        <i class="material-icons left">dashboard</i>Ir para o Dashboard
    </a>
@endsection