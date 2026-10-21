<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('titulo', 'Inicio') &middot; {{ config('app.name', 'Art Craft') }}</title>

    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('estilos')
</head>

<body @hasSection('clase-cuerpo')class="@yield('clase-cuerpo')"@endif>
    @if (View::hasSection('contenido-completo'))
        @yield('contenido-completo')
    @else
        @include('partials.header')

        @yield('contenido')

        @include('partials.footer')
    @endif

    @stack('scripts')
</body>

</html>
