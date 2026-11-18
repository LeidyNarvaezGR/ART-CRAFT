
@extends('layouts.app')

@section('titulo', 'Pagar')

@push('estilos')
<link rel="stylesheet" href="{{ asset('css/pagar.css') }}">
@endpush

@section('contenido')
<main class="pago-pagina">

    <header class="pago-cabecera">
        <h1>Finalizar compra</h1>
        <p>Revisa tus datos y confirma tu pedido.</p>
    </header>

    @if (session('mensaje'))
        <p class="alerta alerta-exito pago-alerta">
            {{ session('mensaje') }}
        </p>
    @endif

    <div class="checkout-container">

        <section class="billing-details">
            <h2>Detalles de facturación</h2>

            <div class="campos">

                <div class="campo">
                    <label for="pago-nombre">Nombre completo *</label>
                    <input id="pago-nombre" type="text"
                           placeholder="Nombre y apellidos"
                           autocomplete="name" required>
                </div>

                <div class="campo">
                    <label for="pago-email">Correo electrónico *</label>
                    <input id="pago-email" type="email"
                           placeholder="tuemail@ejemplo.com"
                           autocomplete="email" required>
                </div>

                <div class="campo">
                    <label for="pago-telefono">Teléfono *</label>
                    <input id="pago-telefono" type="tel"
                           placeholder="3001234567"
                           autocomplete="tel" required>
                </div>

                <div class="campo campo-ancho">
                    <label for="pago-direccion">Dirección *</label>
                    <input id="pago-direccion" type="text"
                           placeholder="Dirección de entrega"
                           autocomplete="street-address" required>
                </div>

                <div class="campo">
                    <label for="pago-ciudad">Ciudad *</label>
                    <input id="pago-ciudad" type="text"
                           placeholder="Ciudad"
                           autocomplete="address-level2" required>
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
                        <td>
                            $ {{ number_format($fila['total'], 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">
                            No has agregado productos todavía.
                        </td>
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

            <p class="total">
                Total a pagar:
                <strong>
                    $ {{ number_format($total, 0, ',', '.') }}
                </strong>
            </p>

            <div class="payment-methods">

                <h3>Método de pago</h3>

                <p>Selecciona un método de pago de prueba.</p>

                <input type="radio"
                       name="metodo-pago"
                       id="tab-tarjeta"
                       value="tarjeta"
                       checked>

                <input type="radio"
                       name="metodo-pago"
                       id="tab-transferencia"
                       value="nequi">

                <input type="radio"
                       name="metodo-pago"
                       id="tab-contraentrega"
                       value="pse">

                <div class="tabs">

                    <label for="tab-tarjeta"
                           class="tab-label label-tarjeta">
                        Tarjeta
                    </label>

                    <label for="tab-transferencia"
                           class="tab-label label-transferencia">
                        Nequi
                    </label>

                    <label for="tab-contraentrega"
                           class="tab-label label-contraentrega">
                        PSE
                    </label>

                </div>

                <div class="payment-content">

                    <div class="payment-section seccion-tarjeta">
                        <p>
                            <i class="fa-solid fa-credit-card"></i>
                            Pago simulado con tarjeta.
                        </p>
                        <p>No ingreses datos bancarios reales.</p>
                    </div>

                    <div class="payment-section seccion-transferencia">
                        <p>
                            <i class="fa-solid fa-mobile-screen"></i>
                            Pago simulado con Nequi.
                        </p>
                        <p>No se realizará ninguna transferencia real.</p>
                    </div>

                    <div class="payment-section seccion-contraentrega">
                        <p>
                            <i class="fa-solid fa-building-columns"></i>
                            Pago simulado mediante PSE.
                        </p>
                        <p>No necesitas ingresar credenciales bancarias.</p>
                    </div>

                </div>

                <p>
                    <strong>Modo de demostración:</strong>
                    esta compra no genera cobros reales.
                </p>

                <button id="btn-pagar"
                        class="pay-btn"
                        type="button">
                    Realizar el pedido
                </button>

                <div id="estado-pago"
                     class="estado-pago"
                     hidden></div>

            </div>
        </section>
    </div>

    <p class="pago-volver">
        <a href="{{ route('carrito') }}">
            <i class="fa-solid fa-arrow-left"></i>
            Volver al carrito
        </a>
    </p>

</main>
@endsection

@push('scripts')
<script>
(function () {

    const btnPagar = document.getElementById('btn-pagar');
    const estadoPago = document.getElementById('estado-pago');

    const mostrarEstado = (texto, tipo) => {
        estadoPago.hidden = false;
        estadoPago.className = 'estado-pago ' + (tipo || '');
        estadoPago.textContent = texto;
    };

    btnPagar.addEventListener('click', async function () {

        const nombre = document.getElementById('pago-nombre').value.trim();
        const email = document.getElementById('pago-email').value.trim();
        const telefono = document.getElementById('pago-telefono').value.trim();
        const direccion = document.getElementById('pago-direccion').value.trim();
        const ciudad = document.getElementById('pago-ciudad').value.trim();

        const metodo = document.querySelector(
            'input[name="metodo-pago"]:checked'
        ).value;

        if (!nombre || !email || !telefono || !direccion || !ciudad) {
            mostrarEstado(
                'Por favor completa todos los campos.',
                'error'
            );
            return;
        }

        btnPagar.disabled = true;
        btnPagar.textContent = 'Procesando pedido...';

        try {

            const respuesta = await fetch(
                '{{ route('pago.crear') }}',
                {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        nombre,
                        email,
                        telefono,
                        direccion,
                        ciudad,
                        metodo_pago: metodo
                    })
                }
            );

            const datos = await respuesta.json();

            if (!respuesta.ok || !datos.ok) {
                mostrarEstado(
                    datos.error || datos.message ||
                    'No se pudo crear el pedido.',
                    'error'
                );
                return;
            }

           mostrarEstado(
    'Pago de prueba aprobado. Redirigiendo al comprobante...',
    'exito'
);

        window.location.href = datos.redirect;
         return;
            // La confirmación del pago se conectará
            // en el siguiente paso.

        } catch (error) {

            console.error(error);

            mostrarEstado(
                'No se pudo conectar con el servidor.',
                'error'
            );

        } finally {

            btnPagar.disabled = false;
            btnPagar.textContent = 'Realizar el pedido';

        }

    });

})();
</script>
@endpush
