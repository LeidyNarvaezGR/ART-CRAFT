<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CarritoController extends Controller
{
    /** Costo de envío fijo de la demo. */
    private const ENVIO = 10000;

    public function index(): View
    {
        return view('carrito', $this->datos());
    }

    public function agregar(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'producto_id' => ['required', 'integer', 'exists:productos,id'],
            'cantidad' => ['nullable', 'integer', 'min:1', 'max:99'],
        ]);

        $id = (int) $datos['producto_id'];
        $cantidad = (int) ($datos['cantidad'] ?? 1);
        $carrito = $this->items($request);
        $carrito[$id] = min(99, ($carrito[$id] ?? 0) + $cantidad);

        $request->session()->put('carrito', $carrito);

        if ($request->input('ir_a') === 'pagar') {
            return redirect()->route('pagar')
                ->with('mensaje', 'Producto agregado. Revisa tu pedido antes de pagar.');
        }

        return back()->with('mensaje', 'Producto agregado al carrito.');
    }

    public function actualizar(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'producto_id' => ['required', 'integer', 'exists:productos,id'],
            'cantidad' => ['required', 'integer', 'min:0', 'max:99'],
        ]);

        $id = (int) $datos['producto_id'];
        $cantidad = (int) $datos['cantidad'];
        $carrito = $this->items($request);

        if ($cantidad === 0) {
            unset($carrito[$id]);
        } else {
            $carrito[$id] = $cantidad;
        }

        $request->session()->put('carrito', $carrito);

        return back()->with('mensaje', $cantidad === 0 ? 'Producto eliminado del carrito.' : 'Cantidad actualizada.');
    }

    public function quitar(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'producto_id' => ['required', 'integer', 'exists:productos,id'],
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

    public function pagar(): View
    {
        return view('pagar', $this->datos());
    }

    /** @return array<int, array{producto: Producto, cantidad: int, total: int}> */
    private function items(Request $request): array
    {
        return $request->session()->get('carrito', []);
    }

    /** Arma la lista de productos del carrito con los totales ya calculados. */
    private function datos(): array
    {
        $items = session('carrito', []);

        if ($items === []) {
            return ['filas' => [], 'subtotal' => 0, 'envio' => 0, 'total' => 0, 'unidades' => 0];
        }

        $productos = Producto::whereIn('id', array_keys($items))->get()->keyBy('id');

        $filas = [];
        $subtotal = 0;
        $unidades = 0;

        foreach ($items as $id => $cantidad) {
            $producto = $productos->get($id);

            if (! $producto) {
                continue;
            }

            $total = $producto->precio * $cantidad;
            $subtotal += $total;
            $unidades += $cantidad;

            $filas[] = ['producto' => $producto, 'cantidad' => $cantidad, 'total' => $total];
        }

        $envio = $filas === [] ? 0 : self::ENVIO;

        return [
            'filas' => $filas,
            'subtotal' => $subtotal,
            'envio' => $envio,
            'total' => $subtotal + $envio,
            'unidades' => $unidades,
        ];
    }
}
