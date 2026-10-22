<?php

namespace Tests\Feature;

use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BusquedaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_la_busqueda_encuentra_productos_de_cualquier_categoria(): void
    {
        $this->get('/buscar?q=Aretes')
            ->assertOk()
            ->assertSee('Aretes Blancos Artesanales')
            ->assertSee('productos para');
    }

    public function test_la_busqueda_also_looks_in_the_description(): void
    {
        Producto::factory()->create([
            'nombre' => 'Pieza sin nombre',
            'descripcion' => 'Tejida a mano por las artesanas del Cauca',
        ]);

        $this->get('/buscar?q=tejida')
            ->assertOk()
            ->assertSee('Pieza sin nombre');
    }

    public function test_avisa_cuando_no_encuentra_nada(): void
    {
        $this->get('/buscar?q=zzzz')
            ->assertOk()
            ->assertSee('No encontramos productos con el nombre');
    }

    public function test_el_buscador_del_encabezado_manda_a_la_busqueda_global(): void
    {
        $html = $this->get('/categorias')->assertOk()->getContent();

        $this->assertStringContainsString('action="'.route('busqueda').'"', $html);
        $this->assertStringContainsString('name="q"', $html);
        $this->assertStringContainsString('btn-buscar', $html);
    }
}
