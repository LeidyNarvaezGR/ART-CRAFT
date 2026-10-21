import { useCallback, useState } from 'react';

/** Lee el carrito que el servidor ya tenía en la sesión y lo devuelve como ids. */
function idsDelServidor() {
    const contenedor = document.getElementById('artcraft-inicio');

    if (!contenedor) {
        return [];
    }

    try {
        const ids = JSON.parse(contenedor.dataset.carritoIds || '[]');
        return Array.isArray(ids) ? ids.map(Number) : [];
    } catch {
        return [];
    }
}

/**
 * Carrito de la portada. Además de estado en React, guarda los ids en la
 * sesión de Laravel con fetch, para que /carritoartesano y /pagar muestren
 * el mismo pedido y el total se calcule solo.
 */
export function useCarrito(urlAgregar) {
    const [ids, setIds] = useState(idsDelServidor);

    const guardarEnSesion = useCallback(
        (id) => {
            const token = document.querySelector('meta[name="csrf-token"]')?.content;
            const datos = new FormData();
            datos.append('producto_id', id);
            datos.append('cantidad', 1);

            fetch(urlAgregar, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': token, Accept: 'application/json' },
                body: datos,
            });
        },
        [urlAgregar],
    );

    const agregar = useCallback(
        (id) => {
            setIds((lista) => {
                if (!lista.includes(id)) {
                    guardarEnSesion(id);
                }

                return lista.includes(id) ? lista : [...lista, id];
            });
        },
        [guardarEnSesion],
    );

    return {
        total: ids.length,
        ids,
        yaAgregado: (id) => ids.includes(id),
        agregar,
    };
}