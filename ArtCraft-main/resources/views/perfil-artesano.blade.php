@extends('layouts.app')

@section('titulo', 'Perfil del artesano')

@push('estilos')
<link rel="stylesheet" data-turbo-track="dynamic" href="{{ asset('css/perfil-artesano.css') }}">
@endpush

@section('contenido')
<section class="artisan-banner">
    <img src="{{ asset('imagenes/perfilArtesano2.jpg') }}" alt="Taller de María Gómez" class="banner-bg">
    <div class="banner-capa"></div>

    <div class="banner-content">
        <p class="banner-etiqueta">Perfil verificado</p>
        <h1>Perfil del Artesano</h1>
        <h2 class="artisan-name">María Gómez</h2>
        <p class="artisan-location"><i class="fa-solid fa-location-dot"></i> Artesana de Nariño</p>
    </div>

    <img src="{{ asset('imagenes/Artesana.png') }}" alt="María Gómez" class="banner-retrato">
</section>

<main class="info-container">
    <section class="about-me">
        <h3>Sobre mí</h3>

        <div class="highlight-box">
            <h4>Aprendimos de nuestros ancestros...</h4>
        </div>

        <p class="description">
            Llevo más de 30 años creando artesanías que cuentan la historia de mi tierra.
            Cada pieza se teje y se modela a mano, con técnicas que me enseñaron mi madre y mi abuela
            en los talleres familiares de Nariño.
        </p>

        <ul class="datos-artesana">
            <li><i class="fa-solid fa-basket-shopping"></i> 24 piezas publicadas</li>
            <li><i class="fa-solid fa-star"></i> 4.9 de valoración</li>
            <li><i class="fa-solid fa-calendar-days"></i> 30 años de oficio</li>
        </ul>
    </section>

    <section class="creations">
        <h3>Mis artesanías</h3>

        <div class="creations-grid">
            <figure>
                <img src="{{ asset('imagenes/Mochila.jpg') }}" alt="Mochila">
                <figcaption>Mochila wayuu</figcaption>
            </figure>
            <figure>
                <img src="{{ asset('imagenes/platos.jpg') }}" alt="Platos de cerámica">
                <figcaption>Platos</figcaption>
            </figure>
            <figure>
                <img src="{{ asset('imagenes/Jarron.jpg') }}" alt="Jarrón">
                <figcaption>Jarrón</figcaption>
            </figure>
            <figure>
                <img src="{{ asset('imagenes/Olla.jpg') }}" alt="Olla de barro">
                <figcaption>Olla</figcaption>
            </figure>
        </div>
    </section>
</main>
@endsection
