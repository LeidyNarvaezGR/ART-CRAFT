
@extends('layouts.app')

@section('titulo', 'Mi Perfil - Administrador')

@push('estilos')
<link rel="stylesheet" href="{{ asset('css/dashboard-admin.css') }}">
@endpush

@section('contenido')

<main class="dashboard-admin">

    <div class="dashboard-contenedor">

        <div class="dashboard-encabezado">
            <span class="dashboard-etiqueta">
                <i class="fa-solid fa-user-shield"></i>
                MI CUENTA
            </span>

            <h1>Mi perfil de administrador</h1>

            <p>
                Consulta la información de tu cuenta
                administrativa de ART CRAFT.
            </p>
        </div>

        <div class="perfil-admin-tarjeta">

            <div class="perfil-admin-avatar">
                <i class="fa-solid fa-user-shield"></i>
            </div>

            <h2>{{ auth()->user()->name }}</h2>

            <span class="perfil-admin-rol">
                Administrador
            </span>

            <div class="perfil-admin-datos">

                <div>
                    <strong>Nombre completo</strong>
                    <p>{{ auth()->user()->name }}</p>
                </div>

                <div>
                    <strong>Correo electrónico</strong>
                    <p>{{ auth()->user()->email }}</p>
                </div>

                <div>
                    <strong>Rol de usuario</strong>
                    <p>{{ auth()->user()->rol }}</p>
                </div>

            </div>

            <a href="{{ route('admin.dashboard') }}"
               class="perfil-admin-volver">
                <i class="fa-solid fa-arrow-left"></i>
                Volver al panel
            </a>

        </div>

    </div>

</main>

@endsection
