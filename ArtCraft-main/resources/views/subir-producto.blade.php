@extends('layouts.app')

@section('titulo', 'Subir producto')

@push('estilos')
<link rel="stylesheet" data-turbo-track="dynamic" href="{{ asset('css/subir-producto.css') }}">
@endpush

@section('contenido')
<section class="titulo">
    <h1>Subir nuevo producto</h1>
    <p>Muestra tu arte al mundo</p>
</section>

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
        <img src="{{ asset('imagenes/Mochila.jpg') }}" alt="Mochila Wayuu" class="producto">
        <h3>Mochila Wayuu</h3>
        <p>Precio: $450.000</p>
        <p>Categoría: Tejidos</p>
    </div>

</section>
@endsection
