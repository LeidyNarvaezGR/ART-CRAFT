<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Support\Notificaciones;
use Illuminate\Contracts\View\View;

class InicioController extends Controller
{
    public function index(): View
    {
        $destacados = Producto::destacados()
            ->get()
            ->map(fn (Producto $producto) => [
                'id' => $producto->id,
                'nombre' => $producto->nombre,
                'descripcion' => $producto->descripcion,
                'categoria' => $producto->categoria,
                'precio' => $producto->precio,
                'imagen' => $producto->imagen_url,
                'url' => $producto->url,
            ])
            ->values();

        return view('inicio', [
            'destacados' => $destacados,
            'categorias' => Producto::select('categoria')->distinct()->pluck('categoria'),
            'notificacionesPendientes' => Notificaciones::pendientes(),
            'urls' => [
                'base' => rtrim(asset(''), '/'),
                'inicio' => route('inicio'),
                'categorias' => route('categorias'),
                'busqueda' => route('busqueda'),
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
}
