@extends('layouts.app')

@section('titulo', 'Blog e historias')

@push('estilos')
<link rel="stylesheet" data-turbo-track="dynamic" href="{{ asset('css/blog-historia.css') }}?v={{ filemtime(public_path('css/blog-historia.css')) }}">
@endpush

@section('contenido')
<section class="banner">
    <h1>Artesanías Auténticas</h1>
    <img src="{{ asset('imagenes/blog.jpg') }}" class="imagen-banner" alt="Banner">
  </section>

  <h2 class="subtitulo">Descubre las artesanías más auténticas</h2>

  <section class="contenido">
    <div class="perfil">
      <img src="{{ asset('imagenes/perfilArtesano2.jpg') }}" alt="Perfil Artesano">
      <div class="info-perfil">
        <div class="encabezado">
          <h2>María Gómez</h2>
          <p>Maestra en cerámica con más ventas</p>
        </div>
        <h3>¡Más de 300 ventas!</h3>
        <a href="{{ route('perfil-artesano') }}"><button>Ver perfil</button></a>
      </div>
    </div>

    <div class="articulos">
      <h2>Artículos Recientes</h2>
      <div class="articulo">
        <img src="{{ asset('imagenes/Jarron.jpg') }}" alt="Jarrón">
        <p>La ruta de la cerámica ancestral</p>
      </div>
      <div class="articulo">
        <img src="{{ asset('imagenes/Olla.jpg') }}" alt="Olla">
        <p>Mercados artesanales locales e internacionales</p>
      </div>
    </div>
  </section>
@endsection
