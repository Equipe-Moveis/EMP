{{-- EDITAR USUÁRIO --}}
@extends('layouts.app')

@section('title', 'Editar Usuário')

@section('content')
    <h5>Editar Usuário</h5>

    <form action="{{ route('usuarios.update', $usuario) }}" method="POST" novalidate>
        @csrf
        @method('PUT')
        @include('usuarios._form')
    </form>
@endsection