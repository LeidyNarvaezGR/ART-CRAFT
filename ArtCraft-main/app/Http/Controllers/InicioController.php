<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InicioController extends Controller
{
    public function index(): View
    {
        $destacados = Producto::destacados()
            ->get()
            ->map(fn (Producto $producto) => $this->formatear($producto))
            ->values();

        return view('inicio', [
            'destacados' => $destacados,
            'categorias' => Producto::select('categoria')->distinct()->pluck('categoria'),
            'urls' => [
                'base' => rtrim(asset(''), '/'),
                'inicio' => route('inicio'),
                'categorias' => route('categorias'),
                'busqueda' => route('busqueda'),
                'apiCatalogo' => route('api.catalogo'),
                'carrito' => route('carrito'),
                'carritoAgregar' => route('carrito.agregar'),
                'perfil' => route('perfil-artesano'),
                'subirProducto' => route('subir-producto'),
                'blog' => route('blog'),
                'contacto' => route('contacto'),
                'nosotros' => route('sobre-nosotros'),
                'notificaciones' => route('notificaciones'),
                'admin' => route('admin.login'),
            ],
        ]);
    }

    /** Productos de una categoria (o todos) en JSON, para los filtros de la portada. */
    public function catalogo(Request $request): JsonResponse
    {
        $categoria = $request->query('categoria');

        $productos = Producto::query()
            ->when($categoria, fn ($query) => $query->deCategoria($categoria))
            ->orderBy('nombre')
            ->get()
            ->map(fn (Producto $producto) => $this->formatear($producto))
            ->values();

        return response()->json($productos);
    }

    /** @return array<string, mixed> */
    private function formatear(Producto $producto): array
    {
        return [
            'id' => $producto->id,
            'nombre' => $producto->nombre,
            'descripcion' => $producto->descripcion,
            'categoria' => $producto->categoria,
            'precio' => $producto->precio,
            'imagen' => $producto->imagen_url,
            'url' => $producto->url,
        ];
    }
}
