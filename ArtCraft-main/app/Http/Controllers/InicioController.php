<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Support\Notificaciones;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class InicioController extends Controller
{
    
public function index(): View
{
    $respuesta = Http::timeout(10)->get(
        config('services.artcraft_api.url').'/catalogo'
    );

    $productos = $respuesta->throw()->json();

   $destacados = collect(
    Http::timeout(10)
        ->get(config('services.artcraft_api.url').'/catalogo/destacados')
        ->throw()
        ->json()
);

    $categorias = collect($productos)
        ->pluck('categoria')
        ->unique()
        ->values();

    return view('inicio', [
        'destacados' => $destacados,
        'categorias' => $categorias,
        'notificacionesPendientes' => Notificaciones::pendientes(),
        'urls' => [
            'base' => rtrim(asset(''), '/'),
            'inicio' => route('inicio'),
            'categorias' => route('categorias'),
            'busqueda' => route('busqueda'),
            'apiCatalogo' => route('api.catalogo'),
            'carrito' => route('carrito'),
            'carritoAgregar' => route('carrito.agregar'),
            'perfil' => route('perfil-artesano'),
            'miPerfil' => route('mi-perfil'),
            'subirProducto' => route('subir-producto'),
            'blog' => route('blog'),
            'contacto' => route('contacto'),
            'nosotros' => route('sobre-nosotros'),
            'notificaciones' => route('notificaciones'),
            'acceso' => route('acceso'),
            'salir' => route('salir'),
            'autenticado' => session()->has('usuario'),
            'usuario' => session('usuario.nombre'),
            'rol' => session('usuario.rol', 'cliente'),
        ],
    ]);
}


    /** Productos de una categoria (o todos) en JSON, para los filtros de la portada. */
   
public function catalogo(Request $request): JsonResponse
{
    $respuesta = Http::timeout(10)->get(
        config('services.artcraft_api.url').'/catalogo',
        [
            'categoria' => $request->query('categoria'),
        ]
    );

    return response()->json(
        $respuesta->throw()->json()
    );
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
