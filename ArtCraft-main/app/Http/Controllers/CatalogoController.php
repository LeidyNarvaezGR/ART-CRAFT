<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CatalogoController extends Controller
{
    public function categorias(Request $request): View
    {
        return view('categorias', [
            'productos' => $this->productos('Mochilas', $request->q),
            'categoriaActual' => 'Mochilas',
            'busqueda' => $request->q,
        ]);
    }

    public function bisuteria(Request $request): View
    {
        return view('bisuteria', [
            'productos' => $this->productos('Bisutería', $request->q),
            'categoriaActual' => 'Bisutería',
            'busqueda' => $request->q,
        ]);
    }

    public function ceramica(Request $request): View
    {
        return view('ceramica', [
            'productos' => $this->productos('Cerámica', $request->q),
            'categoriaActual' => 'Cerámica',
            'busqueda' => $request->q,
        ]);
    }

    public function mascaras(Request $request): View
    {
        return view('mascaras', [
            'productos' => $this->productos('Máscaras', $request->q),
            'categoriaActual' => 'Máscaras',
            'busqueda' => $request->q,
        ]);
    }

    public function mostrarProducto(Producto $producto): View
    {
        return $this->detalle($producto, 'mostrar-producto', 'categorias');
    }

    public function mostrarBisuteria(Producto $producto): View
    {
        return $this->detalle($producto, 'mostrar-bisuteria', 'bisuteria');
    }

    public function mostrarCeramica(Producto $producto): View
    {
        return $this->detalle($producto, 'mostrar-ceramica', 'ceramica');
    }

    public function mostrarMascaras(Producto $producto): View
    {
        return $this->detalle($producto, 'mostrar-mascaras', 'mascaras');
    }

    /** Entra a /mostrarproducto sin id: manda al primer producto de la categoría. */
    public function mostrarProductoPorDefecto(Request $request): RedirectResponse
    {
        $producto = Producto::deCategoria($request->route('categoria'))->first()
            ?? Producto::query()->first();

        if (! $producto) {
            return redirect()->route('categorias');
        }

        return redirect()->route($request->route('ruta'), $producto);
    }

    private function detalle(Producto $producto, string $vista, string $rutaVolver): View
    {
        return view($vista, [
            'producto' => $producto,
            'rutaVolver' => $rutaVolver,
        ]);
    }

    private function productos(string $categoria, ?string $busqueda = null)
    {
        return Producto::deCategoria($categoria)
            ->when($busqueda, fn ($query) => $query->where('nombre', 'like', '%'.$busqueda.'%'))
            ->get();
    }
}
