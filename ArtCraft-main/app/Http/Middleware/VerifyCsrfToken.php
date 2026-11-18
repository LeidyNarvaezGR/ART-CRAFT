<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Las URIs que deben excluirse de la verificación CSRF.
     *
     * El webhook de Wompi lo llama su servidor (no el navegador), por lo que no
     * envía el token CSRF de Laravel.
     *
     * @var array<int, string>
     */
    protected $except = [
        'webhook/wompi',
    ];
}
