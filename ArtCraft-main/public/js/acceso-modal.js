/* Ventana pequeña de acceso: se abre al comprar o al pulsar "Iniciar sesión". */
(function () {
    if (window.__artcraftAcceso) {
        return;
    }

    window.__artcraftAcceso = true;

    let autenticado = document.body.dataset.autenticado === '1';
    let pendiente = null;

    const elemento = () => document.getElementById('modal-acceso');
    const token = () => document.querySelector('meta[name="csrf-token"]')?.content || '';

    function pintarPestana(modo) {
        const caja = elemento();

        if (!caja) {
            return;
        }

        caja.querySelectorAll('[data-pestana]').forEach((boton) => {
            const activo = boton.dataset.pestana === modo;
            boton.classList.toggle('activo', activo);
            boton.setAttribute('aria-selected', activo ? 'true' : 'false');
        });

        caja.querySelectorAll('[data-panel]').forEach((panel) => {
            panel.hidden = panel.dataset.panel !== modo;
        });
    }

    function pintarRol(rol) {
        const caja = elemento();

        if (!caja) {
            return;
        }

        const elegido = rol === 'artesano' ? 'artesano' : 'cliente';

        caja.dataset.rolElegido = elegido;

        caja.querySelectorAll('.modal-acceso__rol [data-rol]').forEach((boton) => {
            const activo = boton.dataset.rol === elegido;
            boton.classList.toggle('activo', activo);
            boton.setAttribute('aria-pressed', activo ? 'true' : 'false');
        });

        caja.querySelectorAll('[data-tipo-acceso]').forEach((campo) => {
            campo.value = elegido;
        });
    }

    function abrir(modo = 'login', accion = null, rol = 'cliente') {
        const caja = elemento();

        if (!caja) {
            return;
        }

        pendiente = accion;
        caja.hidden = false;
        caja.setAttribute('aria-hidden', 'false');
        document.body.classList.add('sin-scroll');

        pintarRol(rol);
        pintarPestana(modo);

        requestAnimationFrame(() => caja.classList.add('abierto'));

        window.setTimeout(() => {
            caja.querySelector('[data-panel]:not([hidden]) input')?.focus();
        }, 140);
    }

    function cerrar() {
        const caja = elemento();

        if (!caja) {
            return;
        }

        caja.classList.remove('abierto');
        document.body.classList.remove('sin-scroll');
        pendiente = null;

        window.setTimeout(() => {
            caja.hidden = true;
            caja.setAttribute('aria-hidden', 'true');
        }, 180);
    }

    function continuar() {
        const accion = pendiente;
        const caja = elemento();

        pendiente = null;

        if (caja) {
            caja.classList.remove('abierto');
            caja.hidden = true;
            caja.setAttribute('aria-hidden', 'true');
        }

        document.body.classList.remove('sin-scroll');

        if (accion?.tipo === 'form') {
            accion.el.requestSubmit();
            return;
        }

        if (accion?.tipo === 'url') {
            window.location.assign(accion.url);
            return;
        }

        if (accion?.tipo === 'link') {
            window.location.assign(accion.el.href);
            return;
        }

        window.location.reload();
    }

    function abrirSiHaceFalta() {
        autenticado = document.body.dataset.autenticado === '1';

        if (autenticado || document.body.dataset.abrirAcceso !== '1') {
            return;
        }

        const destino = document.body.dataset.destino;

        window.setTimeout(() => {
            abrir('login', destino ? { tipo: 'url', url: destino } : null);
        }, 250);
    }

    document.addEventListener('click', (evento) => {
        const cerrarBoton = evento.target.closest('[data-cerrar-acceso]');

        if (cerrarBoton) {
            evento.preventDefault();
            cerrar();
            return;
        }

        const pestana = evento.target.closest('[data-pestana]');

        if (pestana) {
            evento.preventDefault();
            pintarPestana(pestana.dataset.pestana);
            return;
        }

        const rolBoton = evento.target.closest('.modal-acceso__rol [data-rol]');

        if (rolBoton) {
            evento.preventDefault();
            pintarRol(rolBoton.dataset.rol);
            return;
        }

        const enlace = evento.target.closest('a[data-abrir-acceso]');

        if (enlace) {
            evento.preventDefault();

            if (autenticado) {
                window.location.assign(enlace.href);
                return;
            }

            abrir(enlace.dataset.modo || 'login', null, enlace.dataset.rolAcceso || 'cliente');
            return;
        }

        const compra = evento.target.closest('a[data-requiere-acceso]');

        if (compra && !autenticado) {
            evento.preventDefault();
            abrir('login', { tipo: 'link', el: compra }, 'cliente');
        }
    }, true);

    document.addEventListener('submit', (evento) => {
        const formulario = evento.target;

        if (!formulario.matches('[data-requiere-acceso]') || autenticado) {
            return;
        }

        evento.preventDefault();
        abrir('login', { tipo: 'form', el: formulario });
    }, true);

    document.addEventListener('submit', async (evento) => {
        const formulario = evento.target;

        if (!formulario.matches('[data-form-acceso]')) {
            return;
        }

        evento.preventDefault();

        const caja = elemento();
        const error = formulario.querySelector('[data-error]');
        const boton = formulario.querySelector('button[type="submit"]');

        if (error) {
            error.textContent = '';
        }

        if (boton) {
            boton.disabled = true;
        }

        try {
            const respuesta = await fetch(formulario.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token(),
                    Accept: 'application/json',
                },
                body: new FormData(formulario),
            });

            if (respuesta.ok) {
                autenticado = true;
                document.body.dataset.autenticado = '1';
                continuar();
                return;
            }

            const datos = await respuesta.json().catch(() => ({}));
            const mensaje = Object.values(datos.errors || {}).flat()[0]
                || 'No pudimos continuar. Revisa los datos e inténtalo de nuevo.';

            if (error) {
                error.textContent = mensaje;
            }
        } catch {
            if (error) {
                error.textContent = 'No pudimos conectar. Inténtalo de nuevo.';
            }
        } finally {
            if (boton) {
                boton.disabled = false;
            }

            if (caja && caja.hidden) {
                caja.classList.remove('abierto');
            }
        }
    }, true);

    document.addEventListener('keydown', (evento) => {
        if (evento.key === 'Escape' && elemento() && !elemento().hidden) {
            cerrar();
        }
    });

    document.addEventListener('turbo:load', abrirSiHaceFalta);
    document.addEventListener('DOMContentLoaded', abrirSiHaceFalta);

    if (document.readyState !== 'loading') {
        abrirSiHaceFalta();
    }
})();
