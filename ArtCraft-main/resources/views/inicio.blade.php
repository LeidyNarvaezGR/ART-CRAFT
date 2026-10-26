@extends('layouts.app')

@section('titulo', 'Inicio')

@push('estilos')
<link rel="stylesheet" data-turbo-track="dynamic" href="{{ asset('css/inicio.css') }}?v={{ filemtime(public_path('css/inicio.css')) }}">
<link rel="stylesheet" data-turbo-track="dynamic" href="{{ asset('css/componentes.css') }}?v={{ filemtime(public_path('css/componentes.css')) }}">
@endpush

@section('contenido-completo')
<div
    id="artcraft-inicio"
<<<<<<< HEAD
    data-destacados="{{ json_encode($destacados) }}"
    data-categorias="{{ json_encode($categorias) }}"
    data-urls="{{ json_encode($urls) }}"
    data-carrito-ids="{{ json_encode(array_keys(session('carrito', []))) }}"
    data-notificaciones="{{ (int) $notificacionesPendientes }}"
=======
    data-destacados="{{ json_encode($destacados, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}"
    data-categorias="{{ json_encode($categorias, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}"
    data-urls="{{ json_encode($urls, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}"
    data-carrito-ids="{{ json_encode(array_keys(session('carrito', [])), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}"
>>>>>>> 8d6b72e39265d088f871e6003b1ddf477b295010
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
