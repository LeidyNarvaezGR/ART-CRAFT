@extends('layouts.app')

@section('titulo', 'Sobre nosotros')

@push('estilos')
<link rel="stylesheet" data-turbo-track="dynamic" href="{{ asset('css/sobre-nosotros.css') }}">
@endpush

@section('contenido')
<section id="hero">
    <h1>Preservando el legado de nuestras raices</h1>
  </section>

<div class="contenedor-sobrenosotros">
  <div class="mision">
  <div class="header">Nuestra Misión</div>
  <p>
    Nuestra misión es apoyar, promover y fortalecer el trabajo de los artesanos colombianos, preservando las tradiciones culturales y el patrimonio ancestral que representan nuestras raíces. Buscamos generar oportunidades de crecimiento económico, formación y visibilidad para las comunidades artesanales, impulsando productos hechos a mano con identidad, creatividad y calidad. A través de nuestras iniciativas, queremos conectar la cultura colombiana con nuevas generaciones y mercados, fomentando el valor del arte artesanal como símbolo de historia, talento y desarrollo sostenible.
  </p>
</div>


  <div class="impacto">
   <div class="header">Nuestro Impacto</div>
     <div class="contenido">
        <img src="{{ asset('imagenes/impacto.png') }}" alt="Ilustración artesanos">
     </div>
  </div>
</div>
@endsection
