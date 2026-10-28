<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('titulo', 'Inicio') &middot; {{ config('app.name', 'Art Craft') }}</title>

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/base.css') }}?v={{ filemtime(public_path('css/base.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/modal-acceso.css') }}?v={{ filemtime(public_path('css/modal-acceso.css')) }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('estilos')

    {{-- Paleta elegante: se carga al final para unificar botones, logo y pie. --}}
    <link rel="stylesheet" href="{{ asset('css/elegante.css') }}?v={{ filemtime(public_path('css/elegante.css')) }}">
</head>

<body data-autenticado="{{ session()->has('usuario') ? '1' : '0' }}"
    @if (session('abrir_acceso')) data-abrir-acceso="1" @if (session('destino_acceso')) data-destino="{{ session('destino_acceso') }}" @endif @endif>
    @php
        $archivosJs = [];
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator(resource_path('js'))) as $archivo) {
            if ($archivo->isFile()) {
                $archivosJs[] = $archivo->getPathname();
            }
        }
        $codigoReciente = $archivosJs ? max(array_map('filemtime', $archivosJs)) : 0;
        $manifiesto = public_path('build/manifest.json');
        $bundleViejo = is_file($manifiesto) && filemtime($manifiesto) < $codigoReciente;
    @endphp

    @if ($bundleViejo)
        <div class="aviso-build">
            El bundle de React est&aacute; desactualizado: ejecuta <code>npm run build</code> y recarga la p&aacute;gina.
        </div>
    @endif
    @if (View::hasSection('contenido-completo'))
        @yield('contenido-completo')
    @else
        @include('partials.header')

        @yield('contenido')

        @include('partials.footer')
    @endif

    @include('partials.modal-acceso')

    <script src="{{ asset('js/acceso-modal.js') }}?v={{ filemtime(public_path('js/acceso-modal.js')) }}" defer></script>

    @stack('scripts')
</body>

</html>
