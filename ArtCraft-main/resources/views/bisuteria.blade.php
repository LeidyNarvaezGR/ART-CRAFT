@extends('layouts.app')

@section('titulo', 'Catálogo de bisutería')

@push('estilos')
<link rel="stylesheet" data-turbo-track="dynamic" href="{{ asset('css/categorias.css') }}?v={{ filemtime(public_path('css/categorias.css')) }}">
@endpush

@section('contenido')
<section class="categorias">
    <h2>Catálogo</h2>

    @include('partials.tabs-categorias')
    @include('partials.grid-productos')
</section>
@endsection
