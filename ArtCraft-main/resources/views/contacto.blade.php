@extends('layouts.app')

@section('titulo', 'Contacto y soporte')

@push('estilos')
<link rel="stylesheet" data-turbo-track="dynamic" href="{{ asset('css/contacto.css') }}?v={{ filemtime(public_path('css/contacto.css')) }}">
@endpush

@section('contenido')
<h1>Contacto y soporte</h1>
<p class="subtitulo">¡Estamos aquí para ayudarte!</p>

@if (session('mensaje'))
<p class="alerta alerta-exito">{{ session('mensaje') }}</p>
@endif

@if ($errors->any())
    <ul class="errores">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<div class="cajas">
    <form class="formulario" action="{{ route('contacto.enviar') }}" method="POST">
        @csrf

        <p>Nombre o Empresa</p>
        <div class="input-box">
            <i class="fa-regular fa-user"></i>
            <input type="text" name="nombre" value="{{ old('nombre') }}" placeholder="Ingresa tu entidad">
        </div>

        <p>Correo Electrónico</p>
        <div class="input-box">
            <i class="fa-solid fa-envelope"></i>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="Ingresa tu correo">
        </div>

        <p>Mensaje</p>
        <textarea name="mensaje" placeholder="Escribe tu mensaje...">{{ old('mensaje') }}</textarea>

        <button type="submit">ENVIAR MENSAJE</button>
    </form>

    <aside class="ayuda">
        <div class="titulo-ayuda">¿Necesitas ayuda?</div>
        <div class="info-ayuda">
            <p><i class="fa-solid fa-phone"></i> +57 325 567 8790</p>
            <p><i class="fa-solid fa-envelope"></i> contacto@gmail.com</p>
            <p><i class="fa-regular fa-clock"></i> Lunes - Viernes 8am - 6pm</p>
        </div>
    </aside>
</div>
@endsection
