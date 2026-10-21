export default function Hero({ urls }) {
    return (
        <section className="hero">
            <img
                className="fondo-hero"
                src={`${urls.base}/imagenes/Inicio.jpg`}
                alt="Artesano trabajando cerámica"
            />

            <div className="contenido-hero">
                <p className="etiqueta">Hecho a mano en Colombia</p>

                <h1>
                    ARTESANÍAS
                    <br />
                    <span>COLOMBIANAS</span>
                </h1>

                <p className="descripcion">Tesoro cultural hecho a mano</p>

                <a href={urls.categorias} className="boton-principal">
                    Ver arte colombiano
                </a>
            </div>
        </section>
    );
}
