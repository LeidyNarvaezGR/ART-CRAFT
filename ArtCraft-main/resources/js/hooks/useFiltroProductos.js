import { useMemo, useState } from 'react';

export const TODAS = 'Todas';

export function useFiltroProductos(productos, categorias) {
    const [busqueda, setBusqueda] = useState('');
    const [categoria, setCategoria] = useState(TODAS);

    const visibles = useMemo(() => {
        const termino = busqueda.trim().toLowerCase();

        return productos.filter((producto) => {
            const coincideCategoria = categoria === TODAS || producto.categoria === categoria;

            const coincideTexto =
                termino === '' ||
                [producto.nombre, producto.categoria, producto.descripcion]
                    .join(' ')
                    .toLowerCase()
                    .includes(termino);

            return coincideCategoria && coincideTexto;
        });
    }, [productos, busqueda, categoria]);

    const limpiar = () => {
        setBusqueda('');
        setCategoria(TODAS);
    };

    return {
        busqueda,
        setBusqueda,
        categoria,
        setCategoria,
        opciones: [TODAS, ...categorias],
        visibles,
        hayFiltros: busqueda.trim() !== '' || categoria !== TODAS,
        total: productos.length,
        limpiar,
    };
}
