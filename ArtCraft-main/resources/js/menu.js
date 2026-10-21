const inicializarMenu = () => {
    const contenedor = document.querySelector('.menu-container');
    const boton = contenedor?.querySelector('.btn-menu');

    if (!contenedor || !boton || boton.dataset.menuListo === 'si') {
        return;
    }

    boton.dataset.menuListo = 'si';

    boton.addEventListener('click', (evento) => {
        evento.preventDefault();
        contenedor.classList.toggle('activo');
    });

    document.addEventListener('click', (evento) => {
        if (!contenedor.contains(evento.target)) {
            contenedor.classList.remove('activo');
        }
    });

    document.addEventListener('keydown', (evento) => {
        if (evento.key === 'Escape') {
            contenedor.classList.remove('activo');
        }
    });
};

inicializarMenu();

document.addEventListener('turbo:load', inicializarMenu);
