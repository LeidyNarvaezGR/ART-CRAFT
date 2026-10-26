<footer class="pie-pagina">
    <div class="grupo-1">
        <div class="box">
            <figure>
                <a href="{{ route('inicio') }}" title="Art Craft">
                    <img class="logo-footer" src="{{ asset('imagenes/logo-artcraft.png') }}?v=4"
                        alt="Art Craft - Manos que crean, historias que inspiran">
                </a>
            </figure>
        </div>

        <div class="box">
            <h2>SÍGUENOS</h2>
            <div class="red-social">
                <a href="#" data-turbo="false" title="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#" data-turbo="false" title="Instagram"><i class="fa-brands fa-instagram"></i></a>
                <a href="#" data-turbo="false" title="YouTube"><i class="fa-brands fa-youtube"></i></a>
            </div>
        </div>

        <div class="box">
            <h2>CONTÁCTANOS</h2>
            <p><i class="fa-solid fa-location-dot"></i> Popayán, Cauca</p>
            <p><i class="fa-solid fa-phone"></i> +57 300 000 0000</p>
            <p><i class="fa-solid fa-envelope"></i> artcraft@gmail.com</p>
        </div>

        <div class="box">
            <h2>MÉTODOS DE PAGO</h2>
            <div class="red-pagos">
                <a href="#" data-turbo="false" title="Visa"><i class="fa-brands fa-cc-visa"></i></a>
                <a href="#" data-turbo="false" title="Mastercard"><i class="fa-brands fa-cc-mastercard"></i></a>
                <a href="#" data-turbo="false" class="nequi" title="Nequi">N</a>
                <a href="#" data-turbo="false" title="PSE"><i class="fa-solid fa-building-columns"></i></a>
            </div>
        </div>
    </div>

    <div class="grupo-2">
        <small>&copy; {{ date('Y') }} <b>Art Craft</b> &middot; Todos los derechos reservados.</small>
    </div>
</footer>
