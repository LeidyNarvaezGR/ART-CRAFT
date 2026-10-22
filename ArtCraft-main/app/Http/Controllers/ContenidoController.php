<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ContenidoController extends Controller
{
    public function blog(): View
    {
        return view('blog');
    }

    public function contacto(): View
    {
        return view('contacto');
    }

    public function sobreNosotros(): View
    {
        return view('sobre-nosotros');
    }

    public function enviarMensaje(Request $request): RedirectResponse
    {
        $request->validate([
            'nombre' => 'required|string|max:120',
            'email' => 'required|email',
            'mensaje' => 'required|string',
        ]);

        return back()->with('mensaje', 'Mensaje enviado (demo). Gracias por escribirnos.');
    }
}
