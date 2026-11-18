@extends('layouts.app')

@section('titulo', 'Comprobante de pago - ART CRAFT')

@push('estilos')
<link rel="stylesheet" href="{{ asset('css/pagar.css') }}">

<style>
    .resultado-pago {
        text-align: center;
        padding: 40px 25px;
        max-width: 600px;
        margin: 30px auto;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.08);
    }

    .icono-estado {
        font-size: 65px;
        margin-bottom: 18px;
        display: block;
    }

    .estado-aprobado {
        color: #15803d;
    }

    .estado-pendiente {
        color: #b45309;
    }

    .estado-rechazado {
        color: #b91c1c;
    }

    .tabla-resumen {
        width: 100%;
        border-collapse: collapse;
        margin-top: 25px;
    }

    .tabla-resumen th,
    .tabla-resumen td {
        padding: 12px;
        border-bottom: 1px solid #e5e7eb;
        text-align: left;
    }

    .tabla-resumen td {
        text-align: right;
        overflow-wrap: anywhere;
    }

    .aviso-simulacion {
        margin-top: 20px;
        padding: 12px;
        background: #fff7ed;
        border-radius: 8px;
        color: #9a3412;
        font-size: 14px;
    }

    .acciones-pago {
        display: flex;
        justify-content: center;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 25px;
    }

    .btn-principal {
        display: inline-block;
        padding: 12px 22px;
        background: #8c1d0d;
        color: white;
        text-decoration: none;
        border-radius: 7px;
        font-weight: 600;
    }

    .btn-principal:hover {
        background: #681508;
        color: white;
    }

    .btn-secundario {
        background: #6b7280;
    }

    .btn-secundario:hover {
        background: #4b5563;
    }
</style>
@endpush

@section('contenido')

<main class="pago-pagina">

    <header class="pago-cabecera">
        <h1>Comprobante de compra</h1>
        <p>Resumen de tu pedido en ART CRAFT</p>
    </header>

    <div class="resultado-pago">

        @php
            $estadoClave = strtolower(
                (string) ($orden->estado_pago ?? 'pendiente')
            );

            $esAprobado = $estadoClave === 'aprobado';
            $esRechazado = $estadoClave === 'rechazado';
        @endphp

        @if ($esAprobado)

            <span class="icono-estado estado-aprobado">
                <i class="fa-solid fa-circle-check"></i>
            </span>

            <h2>¡Pago de prueba aprobado!</h2>

            <p>
                Tu pedido se registró correctamente.
                Gracias por comprar en ART CRAFT.
            </p>

        @elseif ($esRechazado)

            <span class="icono-estado estado-rechazado">
                <i class="fa-solid fa-circle-xmark"></i>
            </span>

            <h2>Pago de prueba rechazado</h2>

            <p>
                No se pudo confirmar el pago simulado.
            </p>

        @else

            <span class="icono-estado estado-pendiente">
                <i class="fa-solid fa-clock"></i>
            </span>

            <h2>Pago pendiente</h2>

            <p>
                Tu pedido está registrado,
                pero todavía no tiene un pago confirmado.
            </p>

        @endif

        <table class="tabla-resumen">

            <tr>
                <th>Número de orden</th>
                <td>{{ $orden->id }}</td>
            </tr>

            <tr>
                <th>Referencia</th>
                <td>{{ $orden->referencia }}</td>
            </tr>

            <tr>
                <th>Estado del pago</th>
                <td>
                    <strong class="estado-{{ $estadoClave }}">
                        {{ ucfirst($estadoClave) }}
                    </strong>
                </td>
            </tr>

            <tr>
                <th>Total</th>
                <td>
                    <strong>
                        $ {{ number_format($orden->total, 0, ',', '.') }} COP
                    </strong>
                </td>
            </tr>

        </table>

        <div class="aviso-simulacion">
            <i class="fa-solid fa-circle-info"></i>
            Esta transacción es una simulación académica.
            No se realizó ningún cobro de dinero real.
        </div>

        <div class="acciones-pago">

            <a href="{{ route('categorias') }}"
               class="btn-principal">
                Seguir comprando
            </a>

            <a href="{{ route('carrito') }}"
               class="btn-principal btn-secundario">
                Ir al carrito
            </a>

        </div>

    </div>

</main>

@endsection