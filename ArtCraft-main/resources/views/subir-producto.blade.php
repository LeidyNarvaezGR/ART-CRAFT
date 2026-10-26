@extends('layouts.app')

@section('titulo', 'Subir producto')

@push('estilos')
<link rel="stylesheet" data-turbo-track="dynamic" href="{{ asset('css/subir-producto.css') }}?v={{ filemtime(public_path('css/subir-producto.css')) }}">
@endpush

@section('contenido')
<section class="titulo">
    <h1>Subir nuevo producto</h1>
    <p>Muestra tu arte al mundo</p>
</section>

@if (session('mensaje'))
<p class="alerta alerta-exito">{{ session('mensaje') }}</p>
@endif

@if ($errors->any())
    <ul class="errores">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<section class="contenido">

    <form class="formulario" action="{{ route('guardar-producto') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <label for="nombre">Nombre del producto</label>
        <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" placeholder="Ingresa el nombre del producto...">

        <label for="categoria">Categoría</label>
        <select id="categoria" name="categoria">
            <option value="">Seleccionar categoría</option>
            @foreach (['Mochilas', 'Bisutería', 'Cerámica', 'Máscaras', 'Tejidos', 'Madera', 'Pinturas', 'Decoración'] as $opcion)
                <option value="{{ $opcion }}" @selected(old('categoria') === $opcion)>{{ $opcion }}</option>
            @endforeach
        </select>

        <label for="descripcion">Descripción</label>
        <textarea id="descripcion" name="descripcion" placeholder="Descripción del producto..."></textarea>

        <label for="precio">Precio</label>
        <input type="number" id="precio" name="precio" value="{{ old('precio') }}" placeholder="Digite el valor del producto...">

        <label for="imagen" class="subir">
            <i class="fa-solid fa-upload"></i>
            Subir Imagen
        </label>

        <input type="file" id="imagen" name="imagen" accept="image/*" hidden>

        <button type="submit" class="publicar">
            PUBLICAR PRODUCTO
        </button>
    </form>

    <div class="preview">
        <h2>Vista Previa</h2>

        <div class="preview-vacia" id="preview-vacia">
            <i class="fa-regular fa-image"></i>
            <p>Tu imagen aparecerá aquí cuando la subas.</p>
        </div>

        <img id="preview-imagen" class="producto" alt="Vista previa del producto" hidden>

        <h3 id="preview-nombre" hidden></h3>
        <p id="preview-precio" hidden></p>
        <p id="preview-categoria" hidden></p>
    </div>

</section>
@endsection

@push('scripts')
<script>
    (function () {
        const campoImagen = document.getElementById('imagen');
        const imagen = document.getElementById('preview-imagen');
        const vacia = document.getElementById('preview-vacia');
        const nombre = document.getElementById('preview-nombre');
        const precio = document.getElementById('preview-precio');
        const categoria = document.getElementById('preview-categoria');
        const campoNombre = document.getElementById('nombre');
        const campoPrecio = document.getElementById('precio');
        const campoCategoria = document.getElementById('categoria');

        /** La imagen solo se muestra cuando la persona elige un archivo. */
        campoImagen.addEventListener('change', function () {
            const archivo = campoImagen.files && campoImagen.files[0];

            if (!archivo) {
                imagen.hidden = true;
                imagen.removeAttribute('src');
                vacia.hidden = false;
                return;
            }

            imagen.src = URL.createObjectURL(archivo);
            imagen.hidden = false;
            vacia.hidden = true;
        });

        /** Los datos de la vista previa aparecen a medida que se escriben. */
        function actualizarTexto(elemento, valor, prefijo) {
            const texto = (valor || '').trim();

            if (texto === '') {
                elemento.hidden = true;
                elemento.textContent = '';
                return;
            }

            elemento.textContent = prefijo + texto;
            elemento.hidden = false;
        }

        campoNombre.addEventListener('input', function () {
            actualizarTexto(nombre, campoNombre.value, '');
        });

        campoPrecio.addEventListener('input', function () {
            if (campoPrecio.value === '') {
                precio.hidden = true;
                precio.textContent = '';
                return;
            }

            const valor = Number(campoPrecio.value);
            precio.textContent = 'Precio: $ ' + valor.toLocaleString('es-CO');
            precio.hidden = false;
        });

        campoCategoria.addEventListener('change', function () {
            actualizarTexto(categoria, campoCategoria.value, 'Categoría: ');
        });
    })();
</script>
@endpush
