<?php

namespace Tests\Feature;

use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FichaProductoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    /** Categoría, ruta con id y ruta sin id. */
    public static function rutas(): array
    {
        return [
            ['Mochilas', 'mostrar-producto', 'mostrarproducto'],
            ['Bisutería', 'mostrar-bisuteria', 'mostrarbisuteria'],
            ['Cerámica', 'mostrar-ceramica', 'mostrarceramica'],
            ['Máscaras', 'mostrar-mascaras', 'mostrarmascaras'],
        ];
    }

    /** @dataProvider rutas */
    public function test_cada_ficha_muestra_el_producto_solicitado(string $categoria, string $ruta, string $path): void
    {
        $producto = Producto::where('categoria', $categoria)->firstOrFail();

        $response = $this->get(route($ruta, $producto));

        $response->assertOk();
        $response->assertSee($producto->nombre);
        $response->assertSee($producto->imagen, false);
    }

    /** @dataProvider rutas */
    public function test_la_entrada_sin_id_redirige_al_primer_producto(string $categoria, string $ruta, string $path): void
    {
        $this->get("/$path")->assertRedirect(route($ruta, Producto::where('categoria', $categoria)->firstOrFail()));
    }

    public function test_el_numero_de_la_url_corresponde_al_producto_mostrado(): void
    {
        $tercero = Producto::where('categoria', 'Mochilas')->orderBy('id')->skip(2)->firstOrFail();
        $otro = Producto::where('categoria', 'Mochilas')->orderBy('id')->firstOrFail();

        $response = $this->get(route('mostrar-producto', $tercero));

        $response->assertOk();
        $response->assertSee($tercero->nombre);
        $response->assertDontSee($otro->nombre);
    }
}
