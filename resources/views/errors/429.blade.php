{{--
    429 = MUITAS TENTATIVAS. Vem do throttle:5,1 na rota POST /login do web.php:
--}}
@extends('errors.layout')

@php
    $segundos = isset($exception) ? ($exception->getHeaders()['Retry-After'] ?? 60) : 60;
@endphp

@section('codigo', '429')
@section('icone', 'hourglass_top')
@section('titulo', 'Muitas tentativas')
@section('mensagem')
    Você fez muitas tentativas em pouco tempo. Aguarde
    <strong>{{ $segundos }} segundo(s)</strong> e tente novamente.
@endsection

@section('botoes')
    <a href="{{ route('login') }}" class="btn waves-effect waves-light">
        <i class="material-icons left">login</i>Voltar ao login
    </a>
@endsection