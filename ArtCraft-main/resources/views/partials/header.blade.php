<header>
    <nav class="navbar">

        <div class="logo">
            <a href="{{ route('inicio') }}" title="Art Craft">
                <img src="{{ asset('imagenes/logo-color.png') }}?v=2" alt="Logo de Art Craft">
            </a>
        </div>

        <form class="contenedor-busqueda" action="{{ route('categorias') }}" method="GET" role="search">
            <input class="search-box" type="search" name="q" value="{{ request('q') }}"
                placeholder="Buscar producto..." aria-label="Buscar producto">
            <i class="fas fa-search"></i>
        </form>

        <div class="nav-icons">
            <a href="{{ route('perfil-artesano') }}" title="Mi perfil">
                <i class="fa-solid fa-user"></i>
            </a>

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
                    <a href="{{ route('admin.login') }}">Administrador</a>
                </div>
            </div>
        </div>

    </nav>
</header>
