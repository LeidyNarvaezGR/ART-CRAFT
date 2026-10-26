import { useState } from 'react';

const ENLACES_MENU = [
    ['Subir producto', 'subirProducto'],
    ['Perfil del artesano', 'perfil'],
    ['Blog/Historias', 'blog'],
    ['Contacto y soporte', 'contacto'],
    ['Nosotros', 'nosotros'],
    ['Categoría', 'categorias'],
];

const tokenCsrf = () => document.querySelector('meta[name="csrf-token"]')?.content || '';

export default function Navbar({ urls, busqueda, onBusqueda, totalCarrito, totalNotificaciones = 0 }) {
    const [menuAbierto, setMenuAbierto] = useState(false);

    const esArtesano = urls.rol === 'artesano';
    const perfilUrl = esArtesano ? urls.perfil : urls.miPerfil || urls.perfil;
    const perfilTitulo = esArtesano ? 'Perfil del artesano' : 'Mi perfil';

    const cerrarMenu = () => setMenuAbierto(false);

    /** La lupa lleva al catalogo con lo que se escribio en el buscador. */
    const buscarEnCatalogo = () => {
        const destino = `${urls.busqueda}?q=${encodeURIComponent(busqueda.trim())}`;

        if (window.Turbo) {
            window.Turbo.visit(destino);
        } else {
            window.location.href = destino;
        }
    };

    return (
        <header>
            <nav className="navbar">
                <div className="logo">
                    <a href={urls.inicio} title="Art Craft" aria-label="Art Craft - Inicio">
                        <img
                            className="logo-icono"
                            src={`${urls.base}/imagenes/logo-icono.png?v=4`}
                            alt="Icono de Art Craft"
                        />
                        <span className="logo-texto">
                            <span className="logo-nombre">Art Craft</span>
                            <span className="logo-lema">Manos que crean, historias que inspiran</span>
                        </span>
                    </a>
                </div>

                <div className="contenedor-busqueda">
                    <input
                        className="search-box"
                        type="search"
                        value={busqueda}
                        onChange={(evento) => onBusqueda(evento.target.value)}
                        onKeyDown={(evento) => {
                            if (evento.key === 'Enter') buscarEnCatalogo();
                        }}
                        placeholder="Buscar artesanías..."
                        aria-label="Buscar productos"
                    />
                    <button
                        type="button"
                        className="btn-buscar"
                        title="Buscar en el catálogo"
                        aria-label="Buscar productos en el catálogo"
                        onClick={buscarEnCatalogo}
                    >
                        <i className="fas fa-search"></i>
                    </button>
                </div>

                <div className="nav-icons">
                    {urls.autenticado ? (
                        <a href={perfilUrl} title={perfilTitulo}>
                            <i className="fa-solid fa-user"></i>
                        </a>
                    ) : (
                        <a href={urls.acceso} title="Iniciar sesión" data-abrir-acceso>
                            <i className="fa-solid fa-user"></i>
                        </a>
                    )}

                    <a href={urls.notificaciones} title="Notificaciones" className="campana-icono">
                        <i className="fa-solid fa-bell"></i>
                        {totalNotificaciones > 0 && (
                            <span className="contador-notificaciones">{totalNotificaciones}</span>
                        )}
                    </a>

                    <a href={urls.carrito} title="Carrito de compras" className="carrito-icono">
                        <i className="fa-solid fa-cart-shopping"></i>
                        {totalCarrito > 0 && <span className="contador-carrito">{totalCarrito}</span>}
                    </a>

                    <div className={`menu-container ${menuAbierto ? 'activo' : ''}`}>
                        <button
                            type="button"
                            className="btn-menu"
                            title="Menú"
                            aria-expanded={menuAbierto}
                            onClick={(evento) => {
                                evento.preventDefault();
                                setMenuAbierto((abierto) => !abierto);
                            }}
                        >
                            <i className="fa-solid fa-bars"></i>
                        </button>

                        <div className="menu-lateral">
                            {ENLACES_MENU.map(([texto, clave]) => (
                                <a key={clave} href={urls[clave]} onClick={cerrarMenu}>
                                    {texto}
                                </a>
                            ))}

                            {urls.autenticado ? (
                                <div className="menu-usuario">
                                    <span className="menu-usuario-nombre">
                                        <i className="fa-solid fa-circle-user"></i> {urls.usuario}
                                    </span>

                                    <span className="menu-usuario-rol">
                                        <i className={`fa-solid ${esArtesano ? 'fa-palette' : 'fa-bag-shopping'}`}></i>{' '}
                                        {esArtesano ? 'Artesano' : 'Cliente'}
                                    </span>

                                    <a href={perfilUrl} onClick={cerrarMenu}>
                                        {perfilTitulo}
                                    </a>

                                    <form action={urls.salir} method="POST">
                                        <input type="hidden" name="_token" value={tokenCsrf()} />
                                        <button type="submit">Cerrar sesión</button>
                                    </form>
                                </div>
                            ) : (
                                <a href={urls.acceso} data-abrir-acceso onClick={cerrarMenu}>
                                    Iniciar sesión
                                </a>
                            )}
                        </div>
                    </div>
                </div>
            </nav>
        </header>
    );
}
