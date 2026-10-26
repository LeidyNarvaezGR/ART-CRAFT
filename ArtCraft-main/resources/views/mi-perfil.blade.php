@extends('layouts.app')

@section('titulo', 'Mi perfil')

@push('estilos')
<link rel="stylesheet" data-turbo-track="dynamic" href="{{ asset('css/mi-perfil.css') }}?v={{ filemtime(public_path('css/mi-perfil.css')) }}">
@endpush

@section('contenido')
<section class="perfil-hero">
    <div class="perfil-hero__contenido">
        <div class="perfil-hero__avatar" aria-hidden="true">{{ $inicial }}</div>

        <div class="perfil-hero__texto">
            <p class="perfil-hero__eyebrow">Mi cuenta</p>
            <h1>{{ $usuario['nombre'] }}</h1>
            <p class="perfil-hero__email">
                <i class="fa-solid fa-envelope"></i>
                {{ $usuario['email'] ?: 'Sin correo registrado' }}
            </p>
            <span class="perfil-hero__rol">
                <i class="fa-solid fa-bag-shopping"></i> Cliente
            </span>
        </div>
    </div>
</section>

<main class="perfil-contenido">
    <div class="perfil-grid">
        <section class="perfil-card">
            <h2><i class="fa-solid fa-id-card"></i> Mis datos</h2>

            <dl class="perfil-datos">
                <div>
                    <dt>Nombre</dt>
                    <dd>{{ $usuario['nombre'] }}</dd>
                </div>
                <div>
                    <dt>Correo electrónico</dt>
                    <dd>{{ $usuario['email'] ?: 'Sin correo registrado' }}</dd>
                </div>
                <div>
                    <dt>Tipo de cuenta</dt>
                    <dd>Cliente / Comprador</dd>
                </div>
                <div>
                    <dt>Estado</dt>
                    <dd><span class="perfil-pill">Sesión activa</span></dd>
                </div>
            </dl>
        </section>

        <section class="perfil-card">
            <h2><i class="fa-solid fa-chart-simple"></i> Mi actividad</h2>

            <div class="perfil-stats">
                <div class="perfil-stat">
                    <strong>{{ $unidades }}</strong>
                    <span>{{ $unidades === 1 ? 'Producto en el carrito' : 'Productos en el carrito' }}</span>
                </div>

                <div class="perfil-stat">
                    <strong>{{ $pendientes }}</strong>
                    <span>{{ $pendientes === 1 ? 'Notificación nueva' : 'Notificaciones nuevas' }}</span>
                </div>

                <div class="perfil-stat">
                    <strong>$ {{ number_format($subtotal, 0, ',', '.') }}</strong>
                    <span>Subtotal del carrito</span>
                </div>
            </div>
        </section>

        <section class="perfil-card perfil-card--ancha">
            <h2><i class="fa-solid fa-cart-shopping"></i> Mi carrito</h2>

            @if ($filas)
                <ul class="perfil-carrito">
                    @foreach ($filas as $fila)
                        <li>
                            <img src="{{ $fila['producto']->imagen_url }}" alt="{{ $fila['producto']->nombre }}">

                            <div class="perfil-carrito__datos">
                                <strong>{{ $fila['producto']->nombre }}</strong>
                                <span>{{ $fila['cantidad'] }} x {{ $fila['producto']->precio_formateado }}</span>
                            </div>

                            <span class="perfil-carrito__total">$ {{ number_format($fila['total'], 0, ',', '.') }}</span>
                        </li>
                    @endforeach
                </ul>

                <div class="perfil-carrito__pie">
                    <span>
                        Envío: $ {{ number_format($envio, 0, ',', '.') }}
                        <strong>Total: $ {{ number_format($total, 0, ',', '.') }}</strong>
                    </span>

                    <a href="{{ route('pagar') }}" class="perfil-btn">
                        <i class="fa-solid fa-credit-card"></i> Ir a pagar
                    </a>
                </div>
            @else
                <p class="perfil-vacio">Todavía no tienes productos en el carrito.</p>
                <a href="{{ route('categorias') }}" class="perfil-btn">
                    <i class="fa-solid fa-bag-shopping"></i> Explorar artesanías
                </a>
            @endif
        </section>

        <section class="perfil-card">
            <h2><i class="fa-solid fa-box-open"></i> Mis pedidos</h2>

            <p class="perfil-vacio">
                Aún no tienes pedidos confirmados. Cuando finalices una compra, aparecerán aquí.
            </p>

            <a href="{{ route('categorias') }}" class="perfil-btn perfil-btn--claro">
                <i class="fa-solid fa-arrow-left"></i> Seguir comprando
            </a>
        </section>

        <section class="perfil-card">
            <h2><i class="fa-solid fa-gear"></i> Mi sesión</h2>

            <ul class="perfil-enlaces">
                <li>
                    <a href="{{ route('notificaciones') }}">
                        <i class="fa-solid fa-bell"></i> Ver notificaciones
                    </a>
                </li>
                <li>
                    <a href="{{ route('carrito') }}">
                        <i class="fa-solid fa-cart-shopping"></i> Revisar mi carrito
                    </a>
                </li>
                <li>
                    <a href="{{ route('perfil-artesano') }}">
                        <i class="fa-solid fa-palette"></i> Conocer a los artesanos
                    </a>
                </li>
            </ul>

            <form action="{{ route('salir') }}" method="POST" class="perfil-salir">
                @csrf
                <button type="submit">
                    <i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión
                </button>
            </form>
        </section>
    </div>
</main>
@endsection
