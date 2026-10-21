@extends('layouts.app')

@section('titulo', 'Carrito del artesano')

@push('estilos')
<link rel="stylesheet" data-turbo-track="dynamic" href="{{ asset('css/carrito-artesano.css') }}">
@endpush

@section('contenido')
<section class="principal">
    <div class="productos">
        <div class="encabezado-lista">
            <h2>Tus productos</h2>

            <div class="acciones-encabezado">
                <span class="conteo">
                    {{ $unidades }} {{ $unidades === 1 ? 'producto' : 'productos' }}
                </span>

                @if ($filas)
                    <form action="{{ route('carrito.vaciar') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-vaciar">
                            <i class="fa-solid fa-trash-can"></i> Vaciar carrito
                        </button>
                    </form>
                @endif
            </div>
        </div>

        @forelse ($filas as $fila)
            @php($producto = $fila['producto'])
            <article class="producto">
                <a class="marco-imagen" href="{{ $producto->url }}" title="Ver {{ $producto->nombre }}">
                    <img src="{{ $producto->imagen_url }}" alt="{{ $producto->nombre }}" loading="lazy" width="120" height="120">
                </a>

                <div class="detalle">
                    <p class="chip-categoria">{{ $producto->categoria }}</p>

                    <h3><a href="{{ $producto->url }}">{{ $producto->nombre }}</a></h3>

                    <p class="precio-unitario">
                        Precio unitario: <strong>{{ $producto->precio_formateado }}</strong>
                    </p>

                    <div class="cantidad">
                        <span class="etiqueta-cantidad">Cantidad</span>

                        <div class="stepper">
                            <form action="{{ route('carrito.actualizar') }}" method="POST">
                                @csrf
                                <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                                <input type="hidden" name="cantidad" value="{{ max(1, $fila['cantidad'] - 1) }}">
                                <button type="submit" class="stepper-btn" title="Quitar una unidad" aria-label="Quitar una unidad">&minus;</button>
                            </form>

                            <span class="stepper-cantidad">{{ $fila['cantidad'] }}</span>

                            <form action="{{ route('carrito.actualizar') }}" method="POST">
                                @csrf
                                <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                                <input type="hidden" name="cantidad" value="{{ $fila['cantidad'] + 1 }}">
                                <button type="submit" class="stepper-btn" title="Agregar una unidad" aria-label="Agregar una unidad">+</button>
                            </form>
                        </div>

                        <form action="{{ route('carrito.quitar') }}" method="POST">
                            @csrf
                            <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                            <button type="submit" class="eliminar">
                                <i class="fa-solid fa-trash"></i> Eliminar
                            </button>
                        </form>
                    </div>
                </div>

                <div class="subtotal">
                    <span class="subtotal-etiqueta">Subtotal</span>
                    <span class="subtotal-valor">$ {{ number_format($fila['total'], 0, ',', '.') }}</span>
                    <span class="subtotal-detalle">
                        {{ $fila['cantidad'] }} × {{ $producto->precio_formateado }}
                    </span>
                </div>
            </article>
        @empty
            <div class="carrito-vacio">
                <i class="fa-solid fa-cart-shopping"></i>
                <h3>Tu carrito está vacío</h3>
                <p>Agrega productos desde el catálogo y aquí aparecerán organizados.</p>
                <a href="{{ route('categorias') }}" class="pagar">Ver productos</a>
            </div>
        @endforelse
    </div>

    <aside class="resumen">
        <h2>Resumen del pedido</h2>

        <div class="fila">
            <span>Productos ({{ $unidades }}):span>
            <span>$ {{ number_format($subtotal, 0, ',', '.') }}</span>
        </div>

        <div class="fila">
            <span>Envío:</span>
            <span>$ {{ number_format($envio, 0, ',', '.') }}</span>
        </div>

        <div class="fila total-carrito">
            <span>Total:</span>
            <span>$ {{ number_format($total, 0, ',', '.') }}</span>
        </div>

        @if ($filas)
            <a href="{{ route('pagar') }}" class="pagar">
                <i class="fa-solid fa-credit-card"></i> Ir a pagar
            </a>

            <a href="{{ route('categorias') }}" class="seguir-comprando">Seguir comprando</a>
        @endif
    </aside>
</section>

<section class="inferior">
    <div class="datos">
        <h2>Datos del envío</h2>
        <label for="envio-nombre">Nombre completo</label>
        <input id="envio-nombre" type="text" placeholder="Nombre Completo">
        <label for="envio-telefono">Teléfono</label>
        <input id="envio-telefono" type="text" placeholder="Teléfono">
        <label for="envio-direccion">Dirección</label>
        <input id="envio-direccion" type="text" placeholder="Dirección">
    </div>

    <div class="pago">
        <h2>Método de pago</h2>
        <label><input type="radio" name="metodo"> Tarjeta de crédito/Débito</label>
        <label><input type="radio" name="metodo"> Transferencia Bancaria</label>
        <label><input type="radio" name="metodo"> Pago Contraentrega</label>
    </div>
</section>
@endsection