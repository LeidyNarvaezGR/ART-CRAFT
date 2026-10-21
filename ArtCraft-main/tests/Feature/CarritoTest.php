<?php

namespace Tests\Feature;

use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CarritoTest extends TestCase
{
    use RefreshDatabase;

    private Producto $producto;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->producto = Producto::where('categoria', 'Mochilas')->firstOrFail();
    }

    public function test_agregar_un_producto_lo_guarda_en_la_sesion(): void
    {
        $response = $this->post(route('carrito.agregar'), [
            'producto_id' => $this->producto->id,
            'cantidad' => 2,
        ]);

        $response->assertRedirect();
        $this->assertSame([$this->producto->id => 2], session('carrito'));
    }

    public function test_el_carrito_calcula_el_total_con_envio(): void
    {
        $this->post(route('carrito.agregar'), [
            'producto_id' => $this->producto->id,
            'cantidad' => 2,
        ]);

        $total = $this->producto->precio * 2 + 10000;

        $this->get(route('carrito'))
            ->assertOk()
            ->assertSee($this->producto->nombre)
            ->assertSee(number_format($total, 0, ',', '.'), false);
    }

    public function test_se_puede_cambiar_la_cantidad(): void
    {
        $this->post(route('carrito.agregar'), ['producto_id' => $this->producto->id, 'cantidad' => 1]);

        $this->post(route('carrito.actualizar'), ['producto_id' => $this->producto->id, 'cantidad' => 4]);

        $this->assertSame([$this->producto->id => 4], session('carrito'));
    }

    public function test_quitar_y_vaciar_dejan_el_carrito_sin_productos(): void
    {
        $otro = Producto::where('id', '!=', $this->producto->id)->firstOrFail();

        $this->post(route('carrito.agregar'), ['producto_id' => $this->producto->id, 'cantidad' => 2]);
        $this->post(route('carrito.agregar'), ['producto_id' => $otro->id, 'cantidad' => 1]);

        $this->post(route('carrito.quitar'), ['producto_id' => $this->producto->id]);
        $this->assertSame([$otro->id => 1], session('carrito'));

        $this->post(route('carrito.vaciar'));
        $this->assertEmpty(session('carrito'));
    }

    public function test_comprar_ahora_manda_a_pagar_con_el_producto_agregado(): void
    {
        $response = $this->post(route('carrito.agregar'), [
            'producto_id' => $this->producto->id,
            'cantidad' => 1,
            'ir_a' => 'pagar',
        ]);

        $response->assertRedirect(route('pagar'));
        $this->assertSame([$this->producto->id => 1], session('carrito'));
    }

    public function test_la_pagina_de_pago_muestra_el_mismo_total(): void
    {
        $this->post(route('carrito.agregar'), ['producto_id' => $this->producto->id, 'cantidad' => 3]);

        $total = $this->producto->precio * 3 + 10000;

        $this->get(route('pagar'))
            ->assertOk()
            ->assertSee('Total a pagar: $ '.number_format($total, 0, ',', '.'), false);
    }

    public function test_la_portada_entrega_el_carrito_que_ya_tenia_la_sesion(): void
    {
        $this->withSession(['carrito' => [$this->producto->id => 2]]);

        $this->get(route('inicio'))
            ->assertOk()
            ->assertSee('data-carrito-ids="['.$this->producto->id.']"', false);
    }
}
