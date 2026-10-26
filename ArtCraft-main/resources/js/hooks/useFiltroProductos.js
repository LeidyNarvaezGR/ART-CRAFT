import { useEffect, useMemo, useState } from 'react';

export const TODAS = 'Todas';

export function useFiltroProductos(productos, categorias, urlCatalogo) {
    const [busqueda, setBusqueda] = useState('');
    const [categoria, setCategoria] = useState(TODAS);
    const [porCategoria, setPorCategoria] = useState({});
    const [cargando, setCargando] = useState(false);

    // Cuando se elige una categoria se piden sus productos: la portada solo
    // trae los destacados, asi que sin esto "Mascaras" salia vacio.
    useEffect(() => {
        if (categoria === TODAS || !urlCatalogo || porCategoria[categoria]) {
            setCargando(false);

            return undefined;
        }

        let vigente = true;

        setCargando(true);

        fetch(`${urlCatalogo}?categoria=${encodeURIComponent(categoria)}`)
            .then((respuesta) => respuesta.json())
            .then((datos) => {
                if (!vigente) return;

                setPorCategoria((previo) => ({ ...previo, [categoria]: datos }));
                setCargando(false);
            })
            .catch(() => {
                if (vigente) setCargando(false);
            });

        return () => {
            vigente = false;
        };
    }, [categoria, porCategoria, urlCatalogo]);

    const lista = categoria === TODAS ? productos : porCategoria[categoria] || [];

    const visibles = useMemo(() => {
        const termino = busqueda.trim().toLowerCase();

        return lista.filter((producto) => {
            const coincideCategoria = categoria === TODAS || producto.categoria === categoria;

            const coincideTexto =
                termino === '' ||
                [producto.nombre, producto.categoria, producto.descripcion]
                    .join(' ')
                    .toLowerCase()
                    .includes(termino);

            return coincideCategoria && coincideTexto;
        });
    }, [lista, busqueda, categoria]);

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
        cargando,
        hayFiltros: busqueda.trim() !== '' || categoria !== TODAS,
        total: lista.length,
        esCategoria: categoria !== TODAS,
        limpiar,
    };
}
