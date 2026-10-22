@extends('layouts.app')

@section('titulo', 'Inicio')

@push('estilos')
<link rel="stylesheet" data-turbo-track="dynamic" href="{{ asset('css/inicio.css') }}?v={{ filemtime(public_path('css/inicio.css')) }}">
<link rel="stylesheet" data-turbo-track="dynamic" href="{{ asset('css/componentes.css') }}?v={{ filemtime(public_path('css/componentes.css')) }}">
@endpush

@section('contenido-completo')
<div
    id="artcraft-inicio"
    data-destacados="{{ json_encode($destacados) }}"
    data-categorias="{{ json_encode($categorias) }}"
    data-urls="{{ json_encode($urls) }}"
    data-carrito-ids="{{ json_encode(array_keys(session('carrito', []))) }}"
>
    <noscript>
        <p class="alerta">Esta portada necesita JavaScript activado para funcionar.</p>
    </noscript>
</div>
@endsection

@push('scripts')
@viteReactRefresh
@vite('resources/js/inicio.jsx')
@endpush
