@extends('layouts.app')

@section('titulo', 'Catálogo de máscaras')

@push('estilos')
<link rel="stylesheet" data-turbo-track="dynamic" href="{{ asset('css/categorias.css') }}">
@endpush

@section('contenido')
<section class="categorias">
    <h2>Catálogo</h2>

    @include('partials.tabs-categorias')
    @include('partials.grid-productos')
</section>
@endsection
