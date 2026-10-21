import { createRoot } from 'react-dom/client';
import App from './components/App';

const ID_CONTENEDOR = 'artcraft-inicio';

let raiz = null;
let nodoMontado = null;

const leerDatos = () => {
    const contenedor = document.getElementById(ID_CONTENEDOR);

    const leer = (atributo, valorInicial) => {
        try {
            return JSON.parse(contenedor.dataset[atributo]) ?? valorInicial;
        } catch {
            return valorInicial;
        }
    };

    return {
        destacados: leer('destacados', []),
        categorias: leer('categorias', []),
        urls: leer('urls', {}),
    };
};

const montar = () => {
    const contenedor = document.getElementById(ID_CONTENEDOR);

    if (!contenedor || contenedor === nodoMontado) {
        return;
    }

    if (raiz) {
        raiz.unmount();
    }

    raiz = createRoot(contenedor);
    raiz.render(<App {...leerDatos()} />);
    nodoMontado = contenedor;
};

const desmontar = () => {
    if (raiz) {
        raiz.unmount();
    }

    raiz = null;
    nodoMontado = null;
};

montar();

document.addEventListener('turbo:load', montar);
document.addEventListener('turbo:before-cache', desmontar);
