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
        return view('perfil-artesano');
    }

    public function subirProducto(): View
    {
        return view('subir-producto');
    }

    public function guardarProducto(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'nombre' => 'required|string|max:120',
            'categoria' => 'required|string|max:60',
            'precio' => 'required|numeric|min:1',
        ]);

        Notificaciones::agregar(
            'producto',
            'Subiste un producto',
            'Recibimos "'.$datos['nombre'].'" en '.$datos['categoria'].'. Se mostrará en el catálogo al publicarlo.',
            route('subir-producto')
        );

        return back();
    }
}
