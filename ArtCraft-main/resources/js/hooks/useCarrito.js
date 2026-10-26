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

/** Avisos pendientes que ya existen en la sesión al cargar la portada. */
function notificacionesDelServidor() {
    const contenedor = document.getElementById('artcraft-inicio');
    const pendientes = Number(contenedor?.dataset.notificaciones || 0);

    return Number.isFinite(pendientes) ? pendientes : 0;
}

/**
 * Carrito de la portada. Además de estado en React, guarda los ids en la
 * sesión de Laravel con fetch, para que /carritoartesano y /pagar muestren
 * el mismo pedido y el total se calcule solo.
 */
export function useCarrito(urlAgregar) {
    const [ids, setIds] = useState(idsDelServidor);
    const [notificaciones, setNotificaciones] = useState(notificacionesDelServidor);

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
            })
                .then((respuesta) => (respuesta.ok ? respuesta.json() : null))
                .then((datosRespuesta) => {
                    const pendientes = Number(datosRespuesta?.pendientes);

                    if (Number.isFinite(pendientes)) {
                        setNotificaciones(pendientes);
                    }
                })
                .catch(() => {});
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
        notificaciones,
    };
}
