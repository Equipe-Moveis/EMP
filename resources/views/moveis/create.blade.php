{{-- NOVO MÓVEL (era o frmInsMovel.php). --}}
@extends('layouts.app')

@section('title', 'Novo Móvel')

@section('content')
    <h5>Novo Móvel</h5>

    {{-- novalidate: quem valida é o MovelRequest, no servidor. --}}
    <form action="{{ route('moveis.store') }}" method="POST" novalidate>
        @csrf
        @include('moveis._form')
    </form>
@endsection