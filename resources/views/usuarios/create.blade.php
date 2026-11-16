{{-- NOVO USUÁRIO  --}}
@extends('layouts.app')

@section('title', 'Novo Usuário')

@section('content')
    <h5>Novo Usuário</h5>

    <form action="{{ route('usuarios.store') }}" method="POST" novalidate>
        @csrf
        @include('usuarios._form')
    </form>
@endsection