@extends('layouts.app')

@section('titulo', 'Acceso')

@push('estilos')
<link rel="stylesheet" data-turbo-track="dynamic" href="{{ asset('css/acceso.css') }}?v={{ filemtime(public_path('css/acceso.css')) }}">
@endpush

@section('contenido')
<div class="container">

    <section class="login">
        <h2>Acceder</h2>

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

        <form action="{{ route('login') }}" method="POST">
            @csrf

            <label for="tipo">Ingresar como <span>*</span></label>
            <select id="tipo" name="tipo">
                <option value="cliente">Cliente / Comprador</option>
                <option value="artesano">Artesano / Creador</option>
            </select>

            <label for="usuario">Nombre de usuario o correo electrónico <span>*</span></label>
            <input type="text" id="usuario" name="usuario" value="{{ old('usuario') }}">

            <label for="password">Contraseña <span>*</span></label>
            <div class="password-field">
                <input type="password" id="password" name="password">
                <span class="eye"></span>
            </div>

            <button type="submit" class="btn-ingresar">Ingresar</button>

            <div class="extras">
                <label><input type="checkbox" name="recordarme"> Recordarme</label>
                <a href="#" data-turbo="false">¿Perdiste tu clave?</a>
            </div>

            <div class="social-login">
                <p>Ingresa con:</p>
                <div class="social-buttons">
                    <button type="button" class="btn-facebook"><i class="fa-brands fa-facebook-f"></i> Facebook</button>
                    <button type="button" class="btn-google"><i class="fa-brands fa-google"></i> Google</button>
                </div>
            </div>
        </form>
    </section>

    <section class="registro">
        <h2>Registro</h2>
        <p>
            Registrarte en este sitio web te permite acceder al estado e historial de tus pedidos.
            Simplemente completa los campos y te configuraremos una nueva cuenta.
            Sólo te pediremos los datos necesarios para que el proceso de compra sea más rápido y sencillo.
        </p>
        <a href="{{ route('registro') }}" class="btn-registrarse">Registrarse</a>
    </section>

</div>
@endsection
