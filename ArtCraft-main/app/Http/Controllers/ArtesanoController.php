<?php

namespace App\Http\Controllers;

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
        $request->validate([
            'nombre' => 'required|string|max:120',
            'categoria' => 'required|string|max:60',
            'precio' => 'required|numeric|min:1',
        ]);

        return back()->with('mensaje', 'Producto registrado en la demo de Art Craft.');
    }
}
