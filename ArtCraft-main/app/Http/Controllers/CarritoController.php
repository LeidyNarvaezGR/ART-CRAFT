<?php

namespace App\Http\Controllers;

use App\Support\Notificaciones;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CarritoController extends Controller
{
    private const ENVIO = 10000;

    private function apiUrl(): string
    {
        return rtrim(
            config('services.artcraft_api.url', 'http://127.0.0.1:8001/api'),
            '/'
        );
    }

    private function catalogo(): array
    {
        $respuesta = Http::timeout(10)
            ->acceptJson()
            ->get($this->apiUrl() . '/catalogo');

        if (! $respuesta->successful()) {
            throw new \RuntimeException('No se pudo consultar el catálogo.');
        }

        $datos = $respuesta->json();

        $productos = $datos['data']
            ?? $datos['productos']
            ?? $datos;

        if (! is_array($productos)) {
            throw new \RuntimeException('El catálogo tiene un formato inválido.');
        }

        return collect($productos)->keyBy('id')->all();
    }

    private function buscarProducto(int $id): ?array
    {
        return $this->catalogo()[$id] ?? null;
    }

    private function items(Request $request): array
    {
        return $request->session()->get('carrito', []);
    }

    public function index(Request $request): View
    {
        return view('carrito', $this->datos($request));
    }

    public function agregar(Request $request): RedirectResponse|JsonResponse
    {
        $datos = $request->validate([
            'producto_id' => ['required', 'integer', 'min:1'],
            'cantidad' => ['nullable', 'integer', 'min:1', 'max:99'],
        ]);

        $id = (int) $datos['producto_id'];
        $cantidad = (int) ($datos['cantidad'] ?? 1);

        try {
            $producto = $this->buscarProducto($id);
        } catch (\Throwable $e) {
            report($e);

            return $this->errorAgregar(
                $request,
                'No se pudo conectar con el catálogo.'
            );
        }

        if (! $producto) {
            return $this->errorAgregar(
                $request,
                'El producto no existe.'
            );
        }

        $carrito = $this->items($request);

        $carrito[$id] = min(
            99,
            ($carrito[$id] ?? 0) + $cantidad
        );

        $request->session()->put('carrito', $carrito);

        Notificaciones::registrar(
            'carrito',
            'Producto agregado al carrito',
            '"' . $producto['nombre'] . '" ya está en tu carrito.',
            route('carrito')
        );

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'pendientes' => Notificaciones::pendientes(),
                'total_carrito' => array_sum($carrito),
            ]);
        }

        if ($request->input('ir_a') === 'pagar') {
            return redirect()
                ->route('pagar')
                ->with('mensaje', 'Producto agregado. Revisa tu pedido antes de pagar.');
        }

        return back()->with('mensaje', 'Producto agregado al carrito.');
    }

    private function errorAgregar(
        Request $request,
        string $mensaje
    ): RedirectResponse|JsonResponse {
        if ($request->expectsJson()) {
            return response()->json([
                'ok' => false,
                'error' => $mensaje,
            ], 422);
        }

        return back()->with('mensaje', $mensaje);
    }

    public function actualizar(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'producto_id' => ['required', 'integer', 'min:1'],
            'cantidad' => ['required', 'integer', 'min:0', 'max:99'],
        ]);

        $id = (int) $datos['producto_id'];
        $cantidad = (int) $datos['cantidad'];

        $carrito = $this->items($request);

        if ($cantidad === 0) {
            unset($carrito[$id]);
        } elseif (isset($carrito[$id])) {
            $carrito[$id] = $cantidad;
        }

        $request->session()->put('carrito', $carrito);

        return back()->with(
            'mensaje',
            $cantidad === 0
                ? 'Producto eliminado del carrito.'
                : 'Cantidad actualizada.'
        );
    }

    public function quitar(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'producto_id' => ['required', 'integer', 'min:1'],
        ]);

        $carrito = $this->items($request);

        unset($carrito[(int) $datos['producto_id']]);

        $request->session()->put('carrito', $carrito);

        return back()->with('mensaje', 'Producto eliminado del carrito.');
    }

    public function vaciar(Request $request): RedirectResponse
    {
        $request->session()->forget('carrito');

        return back()->with('mensaje', 'Carrito vaciado.');
    }

    public function pagar(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('usuario')) {
            return redirect()
                ->route('carrito')
                ->with('abrir_acceso', true)
                ->with('destino_acceso', route('pagar'))
                ->with('mensaje', 'Inicia sesión para continuar.');
        }

        return redirect()->route('pagar');
    }

    private function datos(Request $request): array
    {
        $items = $this->items($request);

        if (empty($items)) {
            return [
                'filas' => [],
                'subtotal' => 0,
                'envio' => 0,
                'total' => 0,
                'unidades' => 0,
            ];
        }

        try {
            $productos = $this->catalogo();
        } catch (\Throwable $e) {
            report($e);

            return [
                'filas' => [],
                'subtotal' => 0,
                'envio' => 0,
                'total' => 0,
                'unidades' => 0,
            ];
        }

        $filas = [];
        $subtotal = 0;
        $unidades = 0;

        foreach ($items as $id => $cantidad) {
            $producto = $productos[$id] ?? null;

            if (! $producto) {
                continue;
            }

            $producto = (object) $producto;

            $cantidad = (int) $cantidad;
            $totalProducto = (int) $producto->precio * $cantidad;

            $filas[] = [
                'producto' => $producto,
                'cantidad' => $cantidad,
                'total' => $totalProducto,
            ];

            $subtotal += $totalProducto;
            $unidades += $cantidad;
        }

        $envio = empty($filas) ? 0 : self::ENVIO;

        return [
            'filas' => $filas,
            'subtotal' => $subtotal,
            'envio' => $envio,
            'total' => $subtotal + $envio,
            'unidades' => $unidades,
        ];
    }
}
