import { useState } from 'react';

const ENLACES_MENU = [
    ['Subir producto', 'subirProducto'],
    ['Perfil del artesano', 'perfil'],
    ['Blog/Historias', 'blog'],
    ['Contacto y soporte', 'contacto'],
    ['Nosotros', 'nosotros'],
    ['Categoría', 'categorias'],
    ['Administrador', 'admin'],
];

export default function Navbar({ urls, busqueda, onBusqueda, totalCarrito }) {
    const [menuAbierto, setMenuAbierto] = useState(false);

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
                    <a href={urls.inicio} title="Art Craft">
                        <img src={`${urls.base}/imagenes/logo-color.png?v=3`} alt="Logo de Art Craft" />
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
                    <a href={urls.perfil} title="Mi perfil">
                        <i className="fa-solid fa-user"></i>
                    </a>

                    <a href={urls.notificaciones} title="Notificaciones">
                        <i className="fa-solid fa-bell"></i>
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
                        </div>
                    </div>
                </div>
            </nav>
        </header>
    );
}
