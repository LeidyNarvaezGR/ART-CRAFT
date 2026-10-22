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

    public function test_encuentra_tambien_en_singular_y_por_categoria(): void
    {
        // "mochilas" (plural) debe encontrar los productos "Mochila ...".
        $this->get('/buscar?q=mochilas')
            ->assertOk()
            ->assertSee('Mochila Azul con Borlas');

        // Y "mascaras" debe encontrar la categoria "Máscaras".
        $this->get('/buscar?q=mascaras')
            ->assertOk()
            ->assertSee('Máscara Doble Tono');
    }

    public function test_las_imagenes_que_no_existen_caen_a_un_marcador(): void
    {
        $producto = Producto::factory()->create(['imagen' => 'no-existe-esta-foto.jpg']);

        $this->assertStringContainsString('sin-imagen.png', $producto->imagen_url);
    }

    public function test_el_buscador_del_encabezado_manda_a_la_busqueda_global(): void
    {
        $html = $this->get('/categorias')->assertOk()->getContent();

        $this->assertStringContainsString('action="'.route('busqueda').'"', $html);
        $this->assertStringContainsString('name="q"', $html);
        $this->assertStringContainsString('btn-buscar', $html);
    }
}
