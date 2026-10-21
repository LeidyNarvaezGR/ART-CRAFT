import Navbar from './Navbar';
import Hero from './Hero';
import Destacados from './Destacados';
import Footer from './Footer';
import { useCarrito } from '../hooks/useCarrito';
import { useFiltroProductos } from '../hooks/useFiltroProductos';

export default function App({ destacados, categorias, urls }) {
    const carrito = useCarrito(urls.carritoAgregar);
    const filtro = useFiltroProductos(destacados, categorias);

    return (
        <>
            <Navbar
                urls={urls}
                busqueda={filtro.busqueda}
                onBusqueda={filtro.setBusqueda}
                totalCarrito={carrito.total}
            />

            <main className="pagina-inicio">
                <Hero urls={urls} />

                <Destacados
                    productos={filtro.visibles}
                    opciones={filtro.opciones}
                    categoria={filtro.categoria}
                    onCategoria={filtro.setCategoria}
                    onLimpiar={filtro.limpiar}
                    hayFiltros={filtro.hayFiltros}
                    total={filtro.total}
                    carrito={carrito}
                    urls={urls}
                />
            </main>

            <Footer urls={urls} />
        </>
    );
}
