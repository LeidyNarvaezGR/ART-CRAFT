@extends('layouts.app')

@section('titulo', 'Buscar productos')

@push('estilos')
<link rel="stylesheet" data-turbo-track="dynamic" href="{{ asset('css/categorias.css') }}?v={{ filemtime(public_path('css/categorias.css')) }}">
@endpush

@section('contenido')
<section class="categorias">
    <div class="busqueda-cabecera">
        <h2>Buscar productos</h2>

        @if (! empty($busqueda))
            <p class="resultados-busqueda">{{ count($productos) }} {{ count($productos) === 1 ? 'producto' : 'productos' }} para “{{ $busqueda }}”.</p>
        @else
            <p class="resultados-busqueda">Escribe en el buscador del encabezado para encontrar mochilas, bisutería, cerámica o máscaras.</p>
        @endif
    </div>

    @include('partials.tabs-categorias')
    @include('partials.grid-productos')
</section>
@endsection
