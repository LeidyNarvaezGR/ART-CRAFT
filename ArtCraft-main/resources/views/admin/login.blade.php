@extends('layouts.app')

@section('titulo', 'Administrador')

@push('estilos')
<link rel="stylesheet" data-turbo-track="dynamic" href="{{ asset('css/admin.css') }}?v={{ filemtime(public_path('css/admin.css')) }}">
@endpush

@section('contenido')
<main class="admin-login">
    <section class="tarjeta-admin">
        <div class="cabecera-admin">
            <i class="fa-solid fa-shield-halved"></i>
            <h2>Acceso de administrador</h2>
            <p>Ingresa con tu cuenta para gestionar los productos de Art Craft.</p>
        </div>

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

        <form action="{{ route('admin.ingresar') }}" method="POST" data-turbo="false">
            @csrf

            <label for="email">Correo electrónico <span>*</span></label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>

            <label for="password">Contraseña <span>*</span></label>
            <input type="password" id="password" name="password" required>

            <label class="recordar"><input type="checkbox" name="recordarme" value="1"> Recordarme</label>

            <button type="submit" class="btn-admin">Ingresar al panel</button>
        </form>

        <p class="demo-admin"><i class="fa-solid fa-key"></i> Demo: admin@artcraft.com / admin12345</p>
    </section>
</main>
@endsection
