@extends('layouts.app')

@section('titulo', 'Registro de usuario')

@push('estilos')
<link rel="stylesheet" data-turbo-track="dynamic" href="{{ asset('css/registro.css') }}?v={{ filemtime(public_path('css/registro.css')) }}">
@endpush

@section('contenido')
<h1>Crear Cuenta</h1>

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

<form class="formulario" action="{{ route('registrar') }}" method="POST">
    @csrf

    <label for="nombre">Nombre Completo</label>
    <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" placeholder="Tu nombre y apellido">

    <label for="email">Correo Electrónico</label>
    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="ejemplo@correo.com">

    <label for="tipo">Tipo de Usuario</label>
    <select id="tipo" name="tipo">
        <option value="cliente">Cliente / Comprador</option>
        <option value="artesano">Artesano / Creador</option>
    </select>

    <label for="password">Contraseña</label>
    <input type="password" id="password" name="password" placeholder="Mínimo 8 caracteres">

    <button type="submit">Registrarse</button>

    <p>¿Ya tienes una cuenta? <a href="{{ route('acceso') }}">Inicia sesión aquí</a></p>
</form>
@endsection
