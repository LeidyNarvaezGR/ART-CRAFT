<?php

namespace Tests\Feature;

use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaginaPrincipalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_la_portada_carga_con_los_productos_destacados(): void
    {
        $response = $this->get(route('inicio'));

        $response->assertOk();
        $response->assertSee('data-destacados', false);
        $response->assertSee($this->productoDestacado(), false);
        $response->assertSee('data-carrito-ids', false);
    }

    private function productoDestacado(): string
    {
        return Producto::where('destacado', true)->firstOrFail()->nombre;
    }

    public function test_el_catalogo_enlaza_cada_producto_con_su_id(): void
    {
        $producto = Producto::where('categoria', 'Mochilas')->firstOrFail();

        $response = $this->get(route('categorias'));

        $response->assertOk();
        $response->assertSee(route('mostrar-producto', $producto), false);
    }
}
