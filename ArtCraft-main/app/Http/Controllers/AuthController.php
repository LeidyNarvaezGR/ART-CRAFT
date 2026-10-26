<?php

namespace App\Http\Controllers;

use App\Support\Notificaciones;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
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

    public function login(Request $request): RedirectResponse|JsonResponse
    {
        $datos = $request->validate([
            'usuario' => ['required', 'string'],
            'password' => ['required', 'string'],
            'tipo' => ['nullable', 'in:cliente,artesano'],
        ]);

        $usuario = (string) $datos['usuario'];
        $rol = $datos['tipo'] ?? 'cliente';
        $email = filter_var($usuario, FILTER_VALIDATE_EMAIL) ? $usuario : null;
        $nombre = $email ? ucfirst(strstr($usuario, '@', true)) : $usuario;

        $request->session()->put('usuario', [
            'nombre' => $nombre,
            'email' => $email,
            'rol' => $rol,
        ]);

        Notificaciones::registrar(
            'cuenta',
            'Sesión iniciada',
            'Ingresaste como '.$usuario.' ('.($rol === 'artesano' ? 'artesano' : 'cliente').').',
            route('inicio')
        );

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'usuario' => $usuario,
                'rol' => $rol,
            ]);
        }

        return back()->with('mensaje', 'Sesión iniciada correctamente (demo).');
    }

    public function registrar(Request $request): RedirectResponse|JsonResponse
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8'],
            'tipo' => ['nullable', 'in:cliente,artesano'],
        ]);

        $rol = $datos['tipo'] ?? 'cliente';

        $request->session()->put('usuario', [
            'nombre' => $datos['nombre'],
            'email' => $datos['email'],
            'rol' => $rol,
        ]);

        Notificaciones::registrar(
            'cuenta',
            'Cuenta creada',
            'Tu cuenta de Art Craft quedó lista, '.$datos['nombre'].' ('.($rol === 'artesano' ? 'artesano' : 'cliente').').',
            route('inicio')
        );

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'usuario' => $datos['nombre'],
                'rol' => $rol,
            ]);
        }

        return back()->with('mensaje', 'Cuenta creada correctamente (demo).');
    }

    public function salir(Request $request): RedirectResponse
    {
        $request->session()->forget('usuario');

        return back()->with('mensaje', 'Sesión cerrada.');
    }
}
