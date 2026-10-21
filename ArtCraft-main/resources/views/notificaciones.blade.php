@extends('layouts.app')

@section('titulo', 'Notificaciones')

@section('clase-cuerpo', 'sin-header-fijo')

@push('estilos')
<link rel="stylesheet" data-turbo-track="dynamic" href="{{ asset('css/notificaciones.css') }}">
@endpush

@section('contenido')
<main class="notificaciones-pagina">
    <header class="cabecera-notificaciones">
        <div>
            <h2><i class="fa-solid fa-bell"></i> Notificaciones</h2>
            <p>
                @if ($pendientes > 0)
                    Tienes {{ $pendientes }} notificación(es) sin leer.
                @else
                    No tienes notificaciones pendientes.
                @endif
            </p>
        </div>

        @if ($pendientes > 0)
            <form action="{{ route('notificaciones.leer') }}" method="POST">
                @csrf
                <button type="submit" class="btn-leer">
                    <i class="fa-solid fa-check-double"></i> Marcar todas como leídas
                </button>
            </form>
        @endif
    </header>

    <ul class="lista-notificaciones">
        @forelse ($notificaciones as $notificacion)
            <li class="notificacion {{ $notificacion['leida'] ? 'leida' : 'sin-leer' }}">
                <span class="icono-notificacion tipo-{{ $notificacion['tipo'] }}">
                    <i class="fa-solid @switch($notificacion['tipo'])
                        @case('producto') fa-box-open @break
                        @case('carrito') fa-cart-shopping @break
                        @case('pedido') fa-truck @break
                        @case('mensaje') fa-envelope @break
                        @case('valoracion') fa-star @break
                        @default fa-bell
                    @endswitch"></i>
                </span>

                <div class="cuerpo-notificacion">
                    <h3>
                        {{ $notificacion['titulo'] }}
                        @unless ($notificacion['leida'])
                            <span class="punto"></span>
                        @endunless
                    </h3>

                    <p>{{ $notificacion['detalle'] }}</p>

                    <small>{{ $notificacion['fecha']->diffForHumans() }}</small>
                </div>

                @if ($notificacion['url'])
                    <a class="enlace-notificacion" href="{{ $notificacion['url'] }}">
                        Ver <i class="fa-solid fa-arrow-right"></i>
                    </a>
                @endif
            </li>
        @empty
            <li class="notificacion vacia">
                <i class="fa-regular fa-bell-slash"></i>
                <p>Aún no hay notificaciones para ti.</p>
            </li>
        @endforelse
    </ul>
</main>
@endsection
