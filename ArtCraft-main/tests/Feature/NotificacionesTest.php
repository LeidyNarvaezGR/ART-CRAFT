<?php

namespace Tests\Feature;

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

    public function test_la_pagina_de_notificaciones_se_muestra(): void
    {
        $this->get('/notificaciones')
            ->assertOk()
            ->assertSee('Notificaciones')
            ->assertSee('Pedido despachado');
    }

    public function test_las_notificaciones_incluyen_los_ultimos_productos(): void
    {
        $this->get('/notificaciones')
            ->assertOk()
            ->assertSee('Nuevo producto publicado');
    }

    public function test_el_header_muestra_la_campana_con_pendientes_y_desaparece_al_leer(): void
    {
        $this->get('/categorias')->assertOk()->assertSee('contador-notificaciones');

        $this->post('/notificaciones/leer')->assertRedirect();

        $this->get('/categorias')->assertOk()->assertDontSee('contador-notificaciones');
        $this->get('/notificaciones')->assertDontSee('sin leer');
    }
}
