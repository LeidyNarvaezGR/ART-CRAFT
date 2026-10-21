<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check() || auth()->user()->rol !== 'admin') {
            return redirect()
                ->route('admin.login')
                ->withErrors(['admin' => 'Inicia sesión como administrador para continuar.']);
        }

        return $next($request);
    }
}
