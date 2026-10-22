@extends('layouts.app')

@section('titulo', 'Panel de administración')

@push('estilos')
<link rel="stylesheet" data-turbo-track="dynamic" href="{{ asset('css/admin.css') }}?v={{ filemtime(public_path('css/admin.css')) }}">
@endpush

@section('contenido')
<main class="admin-panel">
    <header class="panel-cabecera">
        <div>
            <h2><i class="fa-solid fa-boxes-stacked"></i> Productos</h2>
            <p>{{ $total }} piezas publicadas en el catálogo · {{ auth()->user()->name }}</p>
        </div>

        <div class="panel-acciones">
            <a href="{{ route('inicio') }}" class="btn-panel btn-secundario" data-turbo="false">
                <i class="fa-solid fa-store"></i> Ver tienda
            </a>

            <form action="{{ route('admin.salir') }}" method="POST" data-turbo="false">
                @csrf
                <button type="submit" class="btn-panel btn-salir">
                    <i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión
                </button>
            </form>
        </div>
    </header>

    @if (session('mensaje'))
        <p class="alerta alerta-exito">{{ session('mensaje') }}</p>
    @endif

    <section class="tabla-admin">
        <div class="fila-admin cabecera-fila">
            <span>Producto</span>
            <span>Categoría</span>
            <span>Precio</span>
            <span>Acción</span>
        </div>

        @forelse ($productos as $producto)
            <div class="fila-admin">
                <span class="celda-producto">
                    <img src="{{ $producto->imagen_url }}" alt="{{ $producto->nombre }}">
                    <b>{{ $producto->nombre }}</b>
                </span>

                <span class="celda-categoria">{{ $producto->categoria }}</span>

                <span class="celda-precio">{{ $producto->precio_formateado }}</span>

                <span class="celda-accion">
                    <form action="{{ route('admin.eliminar', $producto) }}" method="POST" data-turbo="false"
                        onsubmit="return confirm('¿Eliminar \"{{ $producto->nombre }}\" del catálogo?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-eliminar">
                            <i class="fa-solid fa-trash"></i> Eliminar
                        </button>
                    </form>
                </span>
            </div>
        @empty
            <p class="vacio-admin">No hay productos en el catálogo.</p>
        @endforelse
    </section>

    {{ $productos->links() }}
</main>
@endsection
