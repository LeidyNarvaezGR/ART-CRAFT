<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function acceso(): View
    {
        return view('acceso');
    }

    public function registro(): View
    {
        return view('registro');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'usuario' => 'required|string',
            'password' => 'required|string',
        ]);

        return back()->with('mensaje', 'Formulario de acceso recibido (demo).');
    }

    public function registrar(Request $request): RedirectResponse
    {
        $request->validate([
            'nombre' => 'required|string|max:120',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        return back()->with('mensaje', 'Formulario de registro recibido (demo).');
    }
}
