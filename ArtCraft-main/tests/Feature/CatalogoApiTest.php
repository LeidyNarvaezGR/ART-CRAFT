<?php

namespace Tests\Feature;

use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogoApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_devuelve_los_productos_de_la_categoria_pedida(): void
    {
        $esperados = Producto::deCategoria('Máscaras')->count();

        $respuesta = $this->getJson('/api/catalogo?categoria=Máscaras')->assertOk();

        $respuesta->assertJsonCount($esperados);
        $this->assertSame('Máscaras', $respuesta->json('0.categoria'));
        $this->assertNotNull($respuesta->json('0.imagen'));
    }

    public function test_sin_categoria_devuelve_todo_el_catalogo(): void
    {
        $respuesta = $this->getJson('/api/catalogo')->assertOk();

        $respuesta->assertJsonCount(Producto::count());
    }

    public function test_la_portada_entrega_la_url_de_la_api(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('api/catalogo', false);
    }
}
