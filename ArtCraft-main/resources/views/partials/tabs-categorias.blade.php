<div class="tabs">
    @foreach (['Mochilas' => 'categorias', 'Bisutería' => 'bisuteria', 'Cerámica' => 'ceramica', 'Máscaras' => 'mascaras'] as $nombre => $ruta)
        <a href="{{ route($ruta) }}">
            <button class="{{ $categoriaActual === $nombre ? 'active' : '' }}">{{ $nombre }}</button>
        </a>
    @endforeach
</div>
