<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function login(): View|RedirectResponse
    {
        if (auth()->check() && auth()->user()->rol === 'admin') {
            return redirect()->route('admin.productos');
        }

        return view('admin.login');
    }

    public function ingresar(Request $request): RedirectResponse
    {
        $credenciales = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (! Auth::attempt($credenciales, $request->boolean('recordarme'))) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Credenciales incorrectas o sin permisos de administrador.']);
        }

        if (auth()->user()->rol !== 'admin') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Esta cuenta no tiene permisos de administrador.']);
        }

        $request->session()->regenerate();

        return redirect()->route('admin.productos')->with('mensaje', 'Bienvenido al panel de administración.');
    }

    public function salir(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('mensaje', 'Sesión cerrada.');
    }

    public function productos(): View
    {
        return view('admin.productos', [
            'productos' => Producto::orderByDesc('id')->paginate(10),
            'total' => Producto::count(),
        ]);
    }

    public function eliminar(Producto $producto): RedirectResponse
    {
        $nombre = $producto->nombre;
        $producto->delete();

        return back()->with('mensaje', "Producto \"{$nombre}\" eliminado.");
    }
}
