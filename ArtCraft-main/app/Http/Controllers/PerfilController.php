<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Support\Notificaciones;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PerfilController extends Controller
{
    /** Costo de envío de la demo (igual que en el carrito). */
    private const ENVIO = 10000;

    public function cliente(Request $request): View|RedirectResponse
    {
        $usuario = $request->session()->get('usuario');

        // Sin sesión: se abre la ventana de acceso.
        if (! is_array($usuario)) {
            return redirect()
                ->route('inicio')
                ->with('abrir_acceso', true)
                ->with('destino_acceso', route('mi-perfil'));
        }

        // Un artesano tiene su propio perfil.
        if (($usuario['rol'] ?? 'cliente') === 'artesano') {
            return redirect()->route('perfil-artesano');
        }

        $items = $request->session()->get('carrito', []);
        $productos = $items === []
            ? collect()
            : Producto::whereIn('id', array_keys($items))->get()->keyBy('id');

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

        return view('mi-perfil', [
            'usuario' => $usuario,
            'filas' => $filas,
            'unidades' => $unidades,
            'subtotal' => $subtotal,
            'total' => $subtotal + ($filas === [] ? 0 : self::ENVIO),
            'envio' => $filas === [] ? 0 : self::ENVIO,
            'pendientes' => Notificaciones::pendientes(),
            'inicial' => mb_strtoupper(mb_substr($usuario['nombre'] ?? 'C', 0, 1)),
        ]);
    }
}
