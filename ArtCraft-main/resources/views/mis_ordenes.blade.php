@extends('layouts.app')

@section('titulo', 'Mis pedidos')

@push('estilos')
<style>
    .tabla-ordenes { width: 100%; border-collapse: collapse; margin-top: 20px; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 6px rgba(0,0,0,0.06); }
    .tabla-ordenes th, .tabla-ordenes td { text-align: left; padding: 14px 16px; border-bottom: 1px solid #e5e7eb; }
    .tabla-ordenes th { background: #f8fafc; color: #334155; font-size: 13px; text-transform: uppercase; letter-spacing: 0.05em; }
    .tabla-ordenes td .monto { font-weight: 700; color: #111827; }
    .badge { display: inline-block; padding: 4px 10px; border-radius: 999px; font-size: 12px; font-weight: 700; text-transform: uppercase; }
    .badge-aprobado { background: #dcfce7; color: #15803d; }
    .badge-declinado, .badge-error { background: #fee2e2; color: #b91c1c; }
    .badge-pendiente { background: #fef3c7; color: #b45309; }
    .vacío { text-align: center; padding: 40px 20px; color: #6b7280; }
</style>
@endpush

@section('contenido')
<main class="pago-pagina">
    <header class="pago-cabecera">
        <h1>Mis pedidos</h1>
        <p>Consulta el estado de tus compras en Art Craft.</p>
    </header>

    @if ($ordenes->isEmpty())
        <div class="vacío">
            <i class="fa-solid fa-box-open" style="font-size:48px; margin-bottom:12px; display:block; color:#cbd5e1;"></i>
            <p>No tienes pedidos registrados todavía.</p>
            <a href="{{ route('categorias') }}" style="color:#2563eb; font-weight:600;">Ir a productos</a>
        </div>
    @else
        <table class="tabla-ordenes">
            <thead>
                <tr>
                    <th>Orden</th>
                    <th>Referencia</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Total</th>
                    <th>Ítems</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ordenes as $orden)
                    <tr>
                        <td>#{{ $orden->id }}</td>
                        <td>{{ $orden->referencia }}</td>
                        <td>{{ $orden->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <span class="badge badge-{{ strtolower($orden->estado) }}">
                                {{ ucfirst($orden->estado) }}
                            </span>
                            @if ($orden->wompi_status)
                                <br><small>{{ ucfirst($orden->wompi_status) }}</small>
                            @endif
                        </td>
                        <td class="monto">$ {{ number_format($orden->total, 0, ',', '.') }}</td>
                        <td>{{ $orden->items->count() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</main>
@endsection
