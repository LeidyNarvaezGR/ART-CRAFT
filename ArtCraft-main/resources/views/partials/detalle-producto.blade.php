{{-- Ficha de un producto. La comparten las cuatro páginas de detalle:
     /mostrarproducto, /mostrarbisuteria, /mostrarceramica y /mostrarmascaras.
     Todo sale de la base de datos, así que la imagen siempre es la del producto elegido. --}}
<div class="tarjeta-producto">
    <a href="{{ route($rutaVolver) }}" class="volver" title="Volver al catálogo">
        <i class="fa-solid fa-arrow-left"></i>
    </a>

    @if (session('mensaje'))
        <p class="alerta alerta-exito">{{ session('mensaje') }}</p>
    @endif

    <div class="seccion-superior">
        <div class="imagen-producto">
            <img src="{{ $producto->imagen_url }}" alt="{{ $producto->nombre }}">
        </div>

        <div class="info-producto">
            <h1>{{ $producto->nombre }}</h1>
            <p>{{ $producto->descripcion }}</p>

            <div class="precio">{{ $producto->precio_formateado }}</div>

            <form class="form-accion" action="{{ route('carrito.agregar') }}" method="POST">
                @csrf
                <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                <input type="hidden" name="cantidad" value="1">
                <button type="submit" class="btn btn-carrito">
                    <i class="fa-solid fa-cart-shopping"></i>
                    Agregar al carrito
                </button>
            </form>

            <form class="form-accion" action="{{ route('carrito.agregar') }}" method="POST">
                @csrf
                <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                <input type="hidden" name="cantidad" value="1">
                <input type="hidden" name="ir_a" value="pagar">
                <button type="submit" class="btn btn-comprar">
                    <i class="fa-solid fa-house"></i>
                    Comprar ahora
                </button>
            </form>
        </div>
    </div>

    <div class="opiniones">
        <h2>Opiniones de nuestros clientes</h2>
        <div class="calificacion">4.8 ★★★★☆</div>

        <div class="comentario">
            <strong>Laura G</strong> ★★★★☆ <br>
            Hermosa pieza, me encantó el material y el acabado.
        </div>

        <div class="comentario">
            <strong>Manuel N</strong> ★★★☆☆ <br>
            Muy bonita y los colores son muy llamativos.
        </div>
    </div>

    <div class="reseña-box">
        <textarea placeholder="Deja tu comentario:"></textarea>

        <div class="estrellas">
            <span onclick="seleccionarEstrella(this)">★</span>
            <span onclick="seleccionarEstrella(this)">★</span>
            <span onclick="seleccionarEstrella(this)">★</span>
            <span onclick="seleccionarEstrella(this)">★</span>
            <span onclick="seleccionarEstrella(this)">★</span>
        </div>

        <button class="btn-enviar" onclick="seleccionarBoton(this)">
            Enviar Reseña
        </button>
    </div>
</div>

<script>
    function seleccionarBoton(boton) {
        document.querySelectorAll('.btn, .btn-enviar').forEach((btn) => btn.classList.remove('activo'));
        boton.classList.add('activo');
    }

    function seleccionarEstrella(estrella) {
        const estrellas = estrella.parentElement.querySelectorAll('span');
        const elegidas = [...estrellas].indexOf(estrella);

        estrellas.forEach((s, index) => {
            s.classList.toggle('seleccionada', index <= elegidas);
        });
    }
</script>