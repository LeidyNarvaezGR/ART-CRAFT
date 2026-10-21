<?php

namespace Tests\Feature;

use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificacionesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_la_pagina_de_notificaciones_se_muestra_vacia_al_inicio(): void
    {
        $this->get('/notificaciones')
            ->assertOk()
            ->assertSee('Notificaciones')
            ->assertSee('Aún no hay notificaciones para ti.');
    }

    public function test_agregar_al_carrito_genera_una_notificacion_real(): void
    {
        $producto = Producto::firstOrFail();

        $this->post('/carritoartesano/agregar', ['producto_id' => $producto->id]);

        $this->get('/notificaciones')
            ->assertOk()
            ->assertSee('Producto agregado al carrito')
            ->assertSee($producto->nombre);
    }

    public function test_subir_un_producto_genera_una_notificacion_real(): void
    {
        $this->post('/subirproducto', [
            'nombre' => 'Mochila nueva',
            'categoria' => 'Mochilas',
            'precio' => 15000,
        ])->assertRedirect();

        $this->get('/notificaciones')
            ->assertOk()
            ->assertSee('Subiste un producto');
    }

    public function test_enviar_un_mensaje_genera_una_notificacion_real(): void
    {
        $this->post('/contacto', [
            'nombre' => 'Cliente',
            'email' => 'cliente@example.com',
            'mensaje' => 'Hola, tengo una duda.',
        ])->assertRedirect();

        $this->get('/notificaciones')
            ->assertOk()
            ->assertSee('Mensaje enviado');
    }

    public function test_el_header_muestra_la_campana_con_pendientes_y_desaparece_al_leer(): void
    {
        $producto = Producto::firstOrFail();
        $this->post('/carritoartesano/agregar', ['producto_id' => $producto->id]);

        $this->get('/categorias')->assertOk()->assertSee('contador-notificaciones');

        $this->post('/notificaciones/leer')->assertRedirect();

        $this->get('/categorias')->assertOk()->assertDontSee('contador-notificaciones');
        $this->get('/notificaciones')->assertDontSee('sin leer');
    }
}
