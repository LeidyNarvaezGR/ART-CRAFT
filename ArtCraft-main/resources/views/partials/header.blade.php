@php
    $usuarioActual = session('usuario');
    $esArtesano = is_array($usuarioActual) && ($usuarioActual['rol'] ?? 'cliente') === 'artesano';
@endphp
<header>
    <nav class="navbar">

        <div class="logo">
            <a href="{{ route('inicio') }}" title="Art Craft" aria-label="Art Craft - Inicio">
                <img class="logo-icono" src="{{ asset('imagenes/logo-icono.png') }}?v=4" alt="Icono de Art Craft">
                <span class="logo-texto">
                    <span class="logo-nombre">Art Craft</span>
                    <span class="logo-lema">Manos que crean, historias que inspiran</span>
                </span>
            </a>
        </div>

        <form class="contenedor-busqueda" action="{{ route('busqueda') }}" method="GET" role="search">
            <input class="search-box" type="search" name="q" value="{{ request('q') }}"
                placeholder="Buscar artesanías..." aria-label="Buscar productos">
            <button class="btn-buscar" type="submit" title="Buscar" aria-label="Buscar productos">
                <i class="fas fa-search"></i>
            </button>
        </form>

        <div class="nav-icons">
            @if ($usuarioActual)
                <a href="{{ $esArtesano ? route('perfil-artesano') : route('mi-perfil') }}"
                    title="{{ $esArtesano ? 'Perfil del artesano' : 'Mi perfil' }}">
                    <i class="fa-solid fa-user"></i>
                </a>
            @else
                <a href="{{ route('acceso') }}" title="Iniciar sesión" data-abrir-acceso>
                    <i class="fa-solid fa-user"></i>
                </a>
            @endif

            <a href="{{ route('notificaciones') }}" title="Notificaciones" class="campana-icono">
                <i class="fa-solid fa-bell"></i>
                @php($pendientes = App\Support\Notificaciones::pendientes())
                @if ($pendientes > 0)
                    <span class="contador-notificaciones">{{ $pendientes }}</span>
                @endif
            </a>

            <a href="{{ route('carrito') }}" title="Carrito de compras" class="carrito-icono">
                <i class="fa-solid fa-cart-shopping"></i>
                @if (session('carrito') && array_sum(session('carrito')) > 0)
                    <span class="contador-carrito">{{ array_sum(session('carrito')) }}</span>
                @endif
            </a>

            <div class="menu-container">
                <a href="#" data-turbo="false" class="btn-menu" title="Menú">
                    <i class="fa-solid fa-bars"></i>
                </a>

                <div class="menu-lateral">
                    <a href="{{ route('subir-producto') }}">Subir producto</a>
                    <a href="{{ route('perfil-artesano') }}">Perfil del artesano</a>
                    <a href="{{ route('blog') }}">Blog/Historias</a>
                    <a href="{{ route('contacto') }}">Contacto y soporte</a>
                    <a href="{{ route('sobre-nosotros') }}">Nosotros</a>
                    <a href="{{ route('categorias') }}">Categoría</a>

                    @if ($usuarioActual)
                        <div class="menu-usuario">
                            <span class="menu-usuario-nombre">
                                <i class="fa-solid fa-circle-user"></i>
                                {{ $usuarioActual['nombre'] }}
                            </span>

                            <span class="menu-usuario-rol">
                                <i class="fa-solid {{ $esArtesano ? 'fa-palette' : 'fa-bag-shopping' }}"></i>
                                {{ $esArtesano ? 'Artesano' : 'Cliente' }}
                            </span>

                            <a href="{{ $esArtesano ? route('perfil-artesano') : route('mi-perfil') }}">
                                {{ $esArtesano ? 'Perfil del artesano' : 'Mi perfil' }}
                            </a>

                            <form action="{{ route('salir') }}" method="POST">
                                @csrf
                                <button type="submit">Cerrar sesión</button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('acceso') }}" data-abrir-acceso>Iniciar sesión</a>
                    @endif
                </div>
            </div>
        </div>

    </nav>
</header>
