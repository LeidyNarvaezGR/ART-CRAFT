import { precioFormato } from '../utils/formato';

export default function ProductoCard({ producto, agregado, onAgregar, urlCarrito }) {
    return (
        <article className="producto">
            <a href={producto.url} className="marco-imagen" title={`Ver ${producto.nombre}`}>
                <img
                    className="imagen-producto"
                    src={producto.imagen}
                    alt={producto.nombre}
                    loading="lazy"
                    width="500"
                    height="500"
                />
            </a>

            <p className="categoria-producto">{producto.categoria}</p>
            <p className="nombre-producto">{producto.nombre}</p>
            <p className="precio-producto">{precioFormato(producto.precio)}</p>

            <div className="acciones-producto">
                <a href={producto.url} className="boton-principal boton-chico">
                    Ver más
                </a>

                {agregado ? (
                    <a href={urlCarrito} className="boton-agregar activo">
                        <i className="fa-solid fa-check"></i>
                        Agregado
                    </a>
                ) : (
                    <button type="button" className="boton-agregar" onClick={() => onAgregar(producto.id)}>
                        <i className="fa-solid fa-cart-plus"></i>
                        Agregar
                    </button>
                )}
            </div>
        </article>
    );
}
