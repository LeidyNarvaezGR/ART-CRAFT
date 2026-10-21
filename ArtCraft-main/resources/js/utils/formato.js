export const precioFormato = (valor) => `$ ${new Intl.NumberFormat('es-CO').format(valor)}`;

export const capitalizar = (texto) => texto.charAt(0).toUpperCase() + texto.slice(1);
