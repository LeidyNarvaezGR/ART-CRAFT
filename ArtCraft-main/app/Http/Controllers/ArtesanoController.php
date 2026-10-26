<?php

namespace App\Http\Controllers;

use App\Support\Notificaciones;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ArtesanoController extends Controller
{
    public function perfil(): View
    {
        $usuario = session('usuario');
        $esArtesano = is_array($usuario) && ($usuario['rol'] ?? 'cliente') === 'artesano';

        return view('perfil-artesano', [
            'esArtesano' => $esArtesano,
            'nombre' => $esArtesano ? $usuario['nombre'] : 'María Gómez',
            'email' => $esArtesano ? ($usuario['email'] ?? null) : null,
        ]);
    }

    public function subirProducto(): View
    {
        return view('subir-producto');
    }

    public function guardarProducto(Request $request): RedirectResponse
    {
        $request->validate([
            'nombre' => 'required|string|max:120',
            'categoria' => 'required|string|max:60',
            'precio' => 'required|numeric|min:1',
        ]);

        Notificaciones::registrar(
            'producto',
            'Producto registrado',
            '"'.$request->input('nombre').'" quedó registrado en la demo de Art Craft.',
            route('perfil-artesano')
        );

        return back()->with('mensaje', 'Producto registrado en la demo de Art Craft.');
    }
}
