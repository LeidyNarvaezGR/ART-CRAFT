<?php

namespace Database\Factories;

use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Producto>
 */
class ProductoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => 'Pieza de prueba '.$this->faker->unique()->numberBetween(1, 9999),
            'descripcion' => $this->faker->sentence(8),
            'categoria' => $this->faker->randomElement(['Mochilas', 'Bisutería', 'Cerámica', 'Máscaras']),
            'precio' => $this->faker->numberBetween(30000, 250000),
            'imagen' => 'Mochila.jpg',
            'ruta_detalle' => 'mostrar-producto',
            'destacado' => $this->faker->boolean(),
        ];
    }
}
