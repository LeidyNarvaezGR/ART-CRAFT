<?php

namespace App\Http\Controllers;

use App\Support\Notificaciones;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NotificacionesController extends Controller
{
    public function index(): View
    {
        return view('notificaciones', [
            'notificaciones' => Notificaciones::todas(),
            'pendientes' => Notificaciones::pendientes(),
        ]);
    }

    public function marcarLeidas(Request $request): RedirectResponse
    {
        $todas = array_column(Notificaciones::todas(), 'id');

        session([
            'notificaciones_leidas' => array_values(array_unique([
                ...$request->session()->get('notificaciones_leidas', []),
                ...$todas,
            ])),
        ]);

        return back()->with('mensaje', 'Todas las notificaciones quedaron marcadas como leídas.');
    }
}
