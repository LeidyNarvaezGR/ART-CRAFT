
@extends('layouts.app')

@section('titulo', 'Panel de Administración')

@push('estilos')
<link rel="stylesheet" href="{{ asset('css/dashboard-admin.css') }}">
@endpush

@section('contenido')

<main class="dashboard-admin">

    <div class="dashboard-contenedor">

        <div class="dashboard-encabezado">
            <span class="dashboard-etiqueta">
                <i class="fa-solid fa-shield-halved"></i>
                PANEL ADMINISTRATIVO
            </span>

            <h1>Bienvenido, {{ auth()->user()->name }}</h1>

            <p>
                Administra tu plataforma ART CRAFT
                desde un solo lugar.
            </p>
        </div>

        @if(session('mensaje'))
            <div class="dashboard-mensaje">
                {{ session('mensaje') }}
            </div>
        @endif

        <div class="dashboard-tarjetas">

            <a href="{{ route('admin.productos') }}"
               class="dashboard-tarjeta">

                <div class="tarjeta-icono">
                    <i class="fa-solid fa-box-open"></i>
                </div>

                <h2>Gestionar productos</h2>

                <p>
                    Consulta, administra y elimina
                    productos de ART CRAFT.
                </p>

                <span>
                    Administrar productos
                    <i class="fa-solid fa-arrow-right"></i>
                </span>

            </a>

            <a href="{{ route('admin.perfil') }}"
               class="dashboard-tarjeta">

                <div class="tarjeta-icono">
                    <i class="fa-solid fa-user-shield"></i>
                </div>

                <h2>Mi perfil</h2>

                <p>
                    Consulta la información de
                    tu cuenta de administrador.
                </p>

                <span>
                    Ver mi perfil
                    <i class="fa-solid fa-arrow-right"></i>
                </span>

            </a>

        </div>

        <div class="dashboard-cuenta">

            <div class="cuenta-informacion">
                <i class="fa-solid fa-circle-check"></i>

                <div>
                    <h3>Sesión de administrador activa</h3>
                    <p>{{ auth()->user()->email }}</p>
                </div>
            </div>

            <form action="{{ route('admin.salir') }}"
                  method="POST">
                @csrf

                <button type="submit" class="btn-cerrar">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    Cerrar sesión
                </button>
            </form>

        </div>

    </div>

</main>

@endsection
