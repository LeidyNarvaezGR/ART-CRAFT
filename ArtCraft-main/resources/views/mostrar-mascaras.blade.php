@extends('layouts.app')

@section('titulo', $producto->nombre)

@push('estilos')
<link rel="stylesheet" data-turbo-track="dynamic" href="{{ asset('css/mostrar-producto.css') }}?v={{ filemtime(public_path('css/mostrar-producto.css')) }}">
@endpush

@section('contenido')
<main>
    @include('partials.detalle-producto')
</main>
@endsection