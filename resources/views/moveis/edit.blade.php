{{-- EDITAR MÓVEL (era o frmEdtMovel.php). --}}
@extends('layouts.app')

@section('title', 'Editar Móvel')

@section('content')
    <h5>Editar Móvel</h5>

    <form action="{{ route('moveis.update', $movel) }}" method="POST" novalidate>
        @csrf
        @method('PUT')
        @include('moveis._form')
    </form>
@endsection