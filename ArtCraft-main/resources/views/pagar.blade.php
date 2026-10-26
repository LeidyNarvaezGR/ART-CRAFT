@extends('layouts.app')

@section('titulo', 'Pagar')

@push('estilos')
<link rel="stylesheet" data-turbo-track="dynamic" href="{{ asset('css/pagar.css') }}?v={{ filemtime(public_path('css/pagar.css')) }}">
@endpush

@section('contenido')
<main class="pago-pagina">
    <header class="pago-cabecera">
        <h1>Finalizar compra</h1>
        <p>Revisa tus datos y confirma tu pedido.</p>
    </header>

    @if (session('mensaje'))
        <p class="alerta alerta-exito pago-alerta">{{ session('mensaje') }}</p>
    @endif

    <div class="checkout-container">
        <section class="billing-details">
            <h2>Detalles de facturación</h2>

            <div class="campos">
                <div class="campo">
                    <label for="pago-nombre">Nombre *</label>
                    <input id="pago-nombre" type="text" placeholder="Nombre" autocomplete="given-name">
                </div>

                <div class="campo">
                    <label for="pago-apellidos">Apellidos *</label>
                    <input id="pago-apellidos" type="text" placeholder="Apellidos" autocomplete="family-name">
                </div>

                <div class="campo">
                    <label for="pago-email">Correo electrónico *</label>
                    <input id="pago-email" type="email" placeholder="tuemail@ejemplo.com" autocomplete="email">
                </div>

                <div class="campo">
                    <label for="pago-telefono">Teléfono *</label>
                    <input id="pago-telefono" type="tel" placeholder="+57" autocomplete="tel">
                </div>

                <div class="campo campo-ancho">
                    <label for="pago-direccion">Dirección *</label>
                    <input id="pago-direccion" type="text" placeholder="Número de casa y calle" autocomplete="street-address">
                </div>

                <div class="campo">
                    <label for="pago-ciudad">Ciudad *</label>
                    <input id="pago-ciudad" type="text" placeholder="Ciudad" autocomplete="address-level2">
                </div>
            </div>
        </section>

        <section class="order-summary">
            <h2>Tu pedido</h2>

            <table>
                <tr>
                    <th>Producto</th>
                    <th>Cant.</th>
                    <th>Subtotal</th>
                </tr>

                @forelse ($filas as $fila)
                    <tr>
                        <td>{{ $fila['producto']->nombre }}</td>
                        <td>{{ $fila['cantidad'] }}</td>
                        <td>$ {{ number_format($fila['total'], 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">No has agregado productos todavía.</td>
                    </tr>
                @endforelse

                <tr>
                    <td>Subtotal</td>
                    <td></td>
                    <td>$ {{ number_format($subtotal, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Envío</td>
                    <td></td>
                    <td>$ {{ number_format($envio, 0, ',', '.') }}</td>
                </tr>
            </table>

            <p class="total">Total a pagar: $ {{ number_format($total, 0, ',', '.') }}</p>

            @unless ($filas)
                <a href="{{ route('categorias') }}" class="volcar-carrito">Ver productos</a>
            @endunless

            <div class="payment-methods">
                <h3>Método de pago</h3>

                <input type="radio" name="metodo-pago" id="tab-tarjeta" checked>
                <input type="radio" name="metodo-pago" id="tab-transferencia">
                <input type="radio" name="metodo-pago" id="tab-contraentrega">

                <div class="tabs">
                    <label for="tab-tarjeta" class="tab-label label-tarjeta">Tarjeta</label>
                    <label for="tab-transferencia" class="tab-label label-transferencia">Transferencia</label>
                    <label for="tab-contraentrega" class="tab-label label-contraentrega">Contraentrega</label>
                </div>

                <div class="payment-content">

                    <div class="payment-section seccion-tarjeta">
                        <div class="card-logos-inline">
                            <img src="{{ asset('imagenes/Visa.png') }}" alt="Visa">
                            <img src="{{ asset('imagenes/Mastercard.png') }}" alt="Mastercard">
                            <img src="{{ asset('imagenes/america-express.png') }}" alt="Amex">
                        </div>
                        <label for="tarjeta-nombre">Nombre de la Tarjeta</label>
                        <input id="tarjeta-nombre" type="text" placeholder="Nombre completo">

                        <label for="tarjeta-numero">Número de la tarjeta</label>
                        <input id="tarjeta-numero" type="text" placeholder="1234 456 45678">

                        <label>Vencimiento</label>
                        <div class="expiry">
                            <input type="text" placeholder="MM" aria-label="Mes de vencimiento">
                            <input type="text" placeholder="AA" aria-label="Año de vencimiento">
                            <input type="text" placeholder="CVV" aria-label="Código CVV">
                        </div>
                    </div>

                    <div class="payment-section seccion-transferencia">
                        <p>Realiza tu pago mediante transferencia bancaria a la siguiente cuenta:</p>
                        <ul>
                            <li><strong>Banco:</strong> Bancolombia</li>
                            <li><strong>Tipo de cuenta:</strong> Ahorros</li>
                            <li><strong>Número:</strong> 123-456-789</li>
                            <li><strong>Titular:</strong> Art Craft S.A.S.</li>
                        </ul>
                        <p>Envía el comprobante al correo <strong>artcraft@gmail.com</strong>.</p>
                    </div>

                    <div class="payment-section seccion-contraentrega">
                        <p>Tu pedido será entregado en la dirección registrada y podrás pagar en efectivo directamente al recibirlo en tu hogar.</p>
                    </div>

                </div>

                <button class="pay-btn" type="button">Realizar el pedido</button>
            </div>
        </section>
    </div>

    <p class="pago-volver">
        <a href="{{ route('carrito') }}">
            <i class="fa-solid fa-arrow-left"></i> Volver al carrito
        </a>
    </p>
</main>
@endsection
