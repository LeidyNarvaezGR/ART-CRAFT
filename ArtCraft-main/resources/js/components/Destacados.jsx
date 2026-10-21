import ProductoCard from './ProductoCard';

export default function Destacados({
    productos,
    opciones,
    categoria,
    onCategoria,
    onLimpiar,
    hayFiltros,
    total,
    carrito,
    urls,
}) {    return (
        <section className="destacados" id="destacados">
            <h2>Destacados del mes</h2>
            <p className="intro-destacados">
                Descubre las piezas artesanales más admiradas por nuestros visitantes.
            </p>

            <div className="filtros-categoria">
                {opciones.map((nombre) => (
                    <button
                        key={nombre}
                        type="button"
                        className={`chip ${categoria === nombre ? 'activo' : ''}`}
                        onClick={() => onCategoria(nombre)}
                    >
                        {nombre}
                    </button>
                ))}

                <span className="contador-carrito-resumen">
                    <i className="fa-solid fa-cart-shopping"></i> {carrito.total} en el carrito
                </span>
            </div>

            {productos.length === 0 ? (
                <div className="sin-resultados">
                    <i className="fa-solid fa-magnifying-glass"></i>
                    <p>No hay productos que coincidan con la búsqueda.</p>
                    <button type="button" className="boton-chico boton-principal" onClick={onLimpiar}>
                        Quitar filtros
                    </button>
                </div>
            ) : (
                <>
                    <p className="conteo-productos">
                        Mostrando {productos.length} de {total} productos
                        {hayFiltros && (
                            <button type="button" className="enlace-limpiar" onClick={onLimpiar}>
                                Quitar filtros
                            </button>
                        )}
                    </p>

                    <div className="productos">
                        {productos.map((producto) => (
                            <ProductoCard
                                key={producto.id}
                                producto={producto}
                                agregado={carrito.yaAgregado(producto.id)}
                                onAgregar={carrito.agregar}
                                urlCarrito={urls.carrito}
                            />
                        ))}
                    </div>
                </>
            )}

            <div className="enlaces-inicio">
                <a href={urls.categorias} className="boton-principal">
                    Ver todo el catálogo
                </a>
            </div>
        </section>
    );
}
