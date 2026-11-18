<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class PagoController extends Controller
{
    private const ENVIO = 10000;

    private function apiUrl(): string
    {
        return rtrim(
            config('services.artcraft_api.url', 'http://127.0.0.1:8001/api'),
            '/'
        );
    }

    private function productosCarrito(Request $request): array
    {
        $carrito = $request->session()->get('carrito', []);

        $items = [];

        foreach ($carrito as $id => $cantidad) {
            if (
                ! filter_var($id, FILTER_VALIDATE_INT) ||
                ! is_numeric($cantidad) ||
                (int) $cantidad < 1 ||
                (int) $cantidad > 99
            ) {
                continue;
            }

            $items[] = [
                'id' => (int) $id,
                'cantidad' => (int) $cantidad,
            ];
        }

        return $items;
    }

    public function index(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('usuario')) {
            return redirect()
                ->route('carrito')
                ->with('abrir_acceso', true)
                ->with('destino_acceso', route('pagar'))
                ->with('mensaje', 'Inicia sesión para continuar.');
        }

        $items = $this->productosCarrito($request);

        if (empty($items)) {
            return redirect()
                ->route('carrito')
                ->with('mensaje', 'Tu carrito está vacío.');
        }

        try {
            $respuesta = Http::timeout(10)
                ->acceptJson()
                ->get($this->apiUrl() . '/catalogo');

            if (! $respuesta->successful()) {
                throw new \RuntimeException('Error al consultar productos.');
            }

            $catalogo = $respuesta->json();

            // Se ajustará según la estructura JSON real del catálogo.
            $productos = $catalogo['data']
                ?? $catalogo['productos']
                ?? $catalogo;

            if (! is_array($productos)) {
                throw new \RuntimeException('Formato de catálogo inválido.');
            }

            $productos = collect($productos)->keyBy('id');

            $filas = [];
            $subtotal = 0;
            $unidades = 0;

            foreach ($items as $item) {
                $producto = $productos->get($item['id']);

                if (! $producto) {
                    throw new \RuntimeException(
                        'Uno de los productos ya no está disponible.'
                    );
                }

                $producto = (object) $producto;
                $cantidad = $item['cantidad'];
                $precio = (int) $producto->precio;
                $totalProducto = $precio * $cantidad;

                $filas[] = [
                    'producto' => $producto,
                    'cantidad' => $cantidad,
                    'total' => $totalProducto,
                ];

                $subtotal += $totalProducto;
                $unidades += $cantidad;
            }

            return view('pagar', [
                'filas' => $filas,
                'subtotal' => $subtotal,
                'envio' => self::ENVIO,
                'total' => $subtotal + self::ENVIO,
                'unidades' => $unidades,
            ]);
        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->route('carrito')
                ->with('mensaje', 'No se pudo cargar el catálogo.');
        }
    }

    public function crear(Request $request): JsonResponse
    {
        if (! app()->environment('local')) {
            return response()->json([
                'ok' => false,
                'error' => 'Los pagos de prueba no están disponibles.',
            ], 403);
        }

        if (! $request->session()->has('usuario')) {
            return response()->json([
                'ok' => false,
                'error' => 'Debes iniciar sesión.',
            ], 401);
        }

        $datos = $request->validate([
            'nombre' => 'required|string|max:120',
            'email' => 'required|email|max:255',
            'telefono' => 'required|string|max:30',
            'direccion' => 'required|string|max:200',
            'ciudad' => 'required|string|max:100',
            'metodo_pago' => 'required|in:tarjeta,nequi,pse',
        ]);

        $items = $this->productosCarrito($request);
        // Generar un identificador único para esta compra.
        $intentoCompra = $request->session()->get('intento_compra');

        if (! $intentoCompra) {
            $intentoCompra = (string) \Illuminate\Support\Str::uuid();

            $request->session()->put(
                'intento_compra',
                $intentoCompra
            );
        }

        if (empty($items)) {
            return response()->json([
                'ok' => false,
                'error' => 'Tu carrito está vacío.',
            ], 422);
        }

        try {
            $respuesta = Http::timeout(15)
                ->acceptJson()
                ->post($this->apiUrl() . '/pago/crear', [
                    ...$datos,
                    'productos' => $items,
                    'intento_compra' => $intentoCompra,
                ]);

            if (! $respuesta->successful()) {
                return response()->json([
                    'ok' => false,
                    'error' => $respuesta->json('message')
                        ?? 'No se pudo crear la orden.',
                ], $respuesta->status());
            }

            $orden = $respuesta->json();

            if (! ($orden['success'] ?? false)) {
                return response()->json([
                    'ok' => false,
                    'error' => 'El backend no confirmó la orden.',
                ], 502);
            }


            // Confirmar el pago de prueba desde el servidor frontend.
            $clave = config('services.artcraft_demo.key');

            if (! is_string($clave) || $clave === '') {
                return response()->json([
                    'ok' => false,
                    'error' => 'Falta configurar la clave de demostración.',
                ], 500);
            }

            $confirmacion = Http::timeout(15)
                ->acceptJson()
                ->withHeaders([
                    'X-Artcraft-Demo-Key' => $clave,
                ])
                ->post($this->apiUrl() . '/pago/confirmar-simulado', [
                    'orden_id' => $orden['orden_id'],
                    'referencia' => $orden['referencia'],
                ]);

            if (
                ! $confirmacion->successful() ||
                $confirmacion->json('estado_pago') !== 'aprobado'
            ) {
                return response()->json([
                    'ok' => false,
                    'error' => 'La orden fue creada, pero el pago no se confirmó. No vuelvas a enviarlo todavía.',
                ], 502);
            }

            $orden['estado_pago'] = 'aprobado';

            $request->session()->put('orden_reciente', $orden);
            $request->session()->forget('intento_compra');

            return response()->json([
                'ok' => true,
                'orden_id' => $orden['orden_id'],
                'referencia' => $orden['referencia'],
                'total' => $orden['total'],
                'estado' => 'aprobado',
                'mensaje' => 'Pago de demostración aprobado.',
                'redirect' => route('pago.respuesta', [
                    'orden' => $orden['orden_id'],
                ]),
            ]);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'ok' => false,
                'error' => 'No se pudo conectar con el backend.',
            ], 502);
        }
    }

    public function respuesta(Request $request, $orden): View
    {
        $reciente = $request->session()->get('orden_reciente');

        if (
            ! is_array($reciente) ||
            (string) ($reciente['orden_id'] ?? '') !== (string) $orden
        ) {
            abort(403, 'No puedes consultar esta orden.');
        }

        return view('pago_respuesta', [
            'orden' => (object) [
                'id' => $reciente['orden_id'],
                'referencia' => $reciente['referencia'],
                'total' => $reciente['total'],
                'estado_pago' => $reciente['estado_pago'],
                'estado' => $reciente['estado_pago'],
            ],
            'estado' => $reciente['estado_pago'],
            'referencia' => $reciente['referencia'],
            'transactionId' => null,
        ]);
    }
}
