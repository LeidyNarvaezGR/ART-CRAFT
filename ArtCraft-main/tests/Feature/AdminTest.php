<?php

namespace Tests\Feature;

use App\Models\Producto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create([
            'email' => 'admin@artcraft.com',
            'password' => Hash::make('admin12345'),
            'rol' => 'admin',
        ]);
    }

    public function test_invitado_es_redirigido_al_login_del_admin(): void
    {
        $this->get('/admin/productos')->assertRedirect('/admin');
    }

    public function test_login_muestra_el_formulario(): void
    {
        $this->get('/admin')->assertOk()->assertSee('Acceso de administrador');
    }

    public function test_ingreso_con_credenciales_validas_lleva_al_panel(): void
    {
        $this->admin();

        $this->post('/admin', [
            'email' => 'admin@artcraft.com',
            'password' => 'admin12345',
        ])->assertRedirect('/admin/productos');

        $this->get('/admin/productos')->assertOk()->assertSee('Productos');
    }

    public function test_ingreso_con_clave_incorrecta_falla(): void
    {
        $this->admin();

        $this->post('/admin', [
            'email' => 'admin@artcraft.com',
            'password' => 'clave-mala',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_usuario_sin_rol_admin_no_puede_borrar(): void
    {
        $this->admin();
        $artesano = User::factory()->create(['rol' => 'artesano']);
        $producto = Producto::factory()->create();

        $this->actingAs($artesano)
            ->delete('/admin/productos/'.$producto->id)
            ->assertRedirect('/admin');

        $this->assertDatabaseHas('productos', ['id' => $producto->id]);
    }

    public function test_admin_puede_borrar_un_producto(): void
    {
        $this->actingAs($this->admin());
        $producto = Producto::factory()->create();

        $this->delete('/admin/productos/'.$producto->id)
            ->assertSessionHas('mensaje');

        $this->assertDatabaseMissing('productos', ['id' => $producto->id]);
    }

    public function test_cerrar_sesion_del_admin(): void
    {
        $this->actingAs($this->admin());

        $this->post('/admin/salir')->assertRedirect('/admin');
        $this->assertGuest();
    }
}
