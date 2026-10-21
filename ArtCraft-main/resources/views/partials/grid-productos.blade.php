<div class="productos">
    @forelse ($productos as $indice => $producto)
        <div class="producto">
            <img src="{{ $producto->imagen_url }}" alt="{{ $producto->nombre }}" loading="lazy">

            <p class="nombre-producto">{{ $producto->nombre }}</p>
            <p class="precio-producto">$ {{ number_format($producto->precio, 0, ',', '.') }}</p>

            <a href="{{ route($producto->ruta_detalle, $producto) }}" class="btn-mostrar">
                Mostrar
            </a>
        </div>
    @empty
        <p class="sin-resultados">
            @if (! empty($busqueda))
                No encontramos productos con el nombre “{{ $busqueda }}”.
            @else
                Todavia no hay productos en esta categoria.
            @endif
        </p>
    @endforelse
</div>
