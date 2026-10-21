<?php

namespace Database\Seeders;

use App\Models\Producto;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    private const PRODUCTOS = [
        // Mochilas
        ['Bolso Wayúu Colorido', 'Mochila tradicional colombiana tejida a mano por mujeres de la comunidad Wayúu, con correa larga y adornos de borlas.', 'Mochilas', 120000, 'mochila-artesanal-1.jpg', 'mostrar-producto', false],
        ['Mochila Negra Artesanal', 'Mochila de fique completamente negra, tejida en crochet por maestras artesanas del Cauca.', 'Mochilas', 95000, 'mochila-artesanal-2.jpg', 'mostrar-producto', true],
        ['Mochila Rosada Wayúu', 'Mochila rosada de tejido tradicional wayúu con detalles bordados a mano.', 'Mochilas', 110000, 'mochila-artesanal-3.jpg', 'mostrar-producto', false],
        ['Mochila Azul Pequeña', 'Mochila azul de tamaño pequeño, ideal para el uso diario, elaborada con fique natural.', 'Mochilas', 85000, 'mochila-artesanal-4.jpg', 'mostrar-producto', true],
        ['Mochila Turquesa Wayúu', 'Mochila turquesa de tejido en crochet con borlas que destacan su diseño.', 'Mochilas', 115000, 'mochila-artesanal-5.jpg', 'mostrar-producto', true],
        ['Mochila Azul con Borlas', 'Mochila azul con borlas colgantes, hilada a mano por artesanas de la región Caribe.', 'Mochilas', 105000, 'mochila-artesanal-6.jpg', 'mostrar-producto', false],

        // Bisutería
        ['Pulsera Artesanal Rosada', 'Pulsera de hilos rosados trenzados a mano, con cierre metálico y detalles bordados.', 'Bisutería', 35000, 'Bisuteria-1.jpg', 'mostrar-bisuteria', false],
        ['Pulsera Artesanal Café', 'Pulsera en tonos café con cuentas de semillas naturales, armada a mano.', 'Bisutería', 32000, 'Bisuteria-2ng.jpg', 'mostrar-bisuteria', false],
        ['Aretes Blancos Artesanales', 'Aretes blancos de oficio artesanal, con pendientes hechos a mano.', 'Bisutería', 45000, 'Bisuteria-3.jpg', 'mostrar-bisuteria', false],
        ['Aretes Coloridos', 'Aretes de colores vivos inspirados en los símbolos de las comunidades andinas.', 'Bisutería', 48000, 'Bisuteria-4.jpg', 'mostrar-bisuteria', false],
        ['Pulsera Verde Artesanal', 'Pulsera verde de hilos vegetales trenzados, un accesorio único y natural.', 'Bisutería', 30000, 'Bisuteria-5.jpg', 'mostrar-bisuteria', false],
        ['Manillas Tejidas', 'Manillas tejidas a mano con hilo de algodón, resistentes y de uso diario.', 'Bisutería', 38000, 'Bisuteria-6png.jpg', 'mostrar-bisuteria', false],

        // Cerámica
        ['Juego de Tazas Artesanales', 'Juego de tazas de barro bruñido a mano, con esmalte natural y detalles tallados.', 'Cerámica', 75000, 'Ceramica-1.jpg', 'mostrar-ceramica', false],
        ['Plato Decorativo Artesanal', 'Plato decorativo de cerámica con diseños pintados a mano por artesanas del Chocó.', 'Cerámica', 60000, 'Ceramica-2.jpg', 'mostrar-ceramica', false],
        ['Set de Cuencos con Cucharas', 'Set de cuencos y cucharas de cerámica, ideales para servir comidas tradicionales.', 'Cerámica', 58000, 'Ceramica-3.jpg', 'mostrar-ceramica', false],
        ['Olla Artesanal de Barro', 'Olla de barro cocido en horno de leña, con asas modeladas a mano.', 'Cerámica', 90000, 'Ceramica-4.jpg', 'mostrar-ceramica', false],
        ['Set de Platos Florales', 'Set de platos rojos con motivos florales pintados a mano, pieza única.', 'Cerámica', 80000, 'Ceramica-5.jpg', 'mostrar-ceramica', false],
        ['Set de Cuencos de Barro', 'Set de cuencos de barro sin esmalte, de textura rugosa y acabado artesanal.', 'Cerámica', 50000, 'Ceramica-6.png', 'mostrar-ceramica', false],

        // Máscaras
        ['Máscara Ceremonial con Tocado', 'Máscara ceremonial de madera con tocado, réplica de la tradición del Chocó.', 'Máscaras', 95000, 'Mascara-1.jpg', 'mostrar-mascaras', false],
        ['Máscara Doble Tono', 'Máscara mitad madera y mitad negra, tallada y pintada a mano.', 'Máscaras', 88000, 'Mascara-2.jpg', 'mostrar-mascaras', false],
        ['Máscara Multicolor Decorativa', 'Máscara decorativa con pinturas de colores vivos y acabado mate.', 'Máscaras', 92000, 'Mascara-3.jpg', 'mostrar-mascaras', false],
        ['Máscara Alargada Artesanal', 'Máscara alargada de madera, tallada en una sola pieza por maestros ancestrales.', 'Máscaras', 80000, 'Mascara-4.jpg', 'mostrar-mascaras', false],
        ['Máscara Negra Tallada', 'Máscara negra de madera tallada, con filtros marcados y diseño ritual.', 'Máscaras', 85000, 'Mascara-5.jpg', 'mostrar-mascaras', false],
        ['Máscara Verde Tradicional', 'Máscara verde tradicional con detalles pintados con pigmentos naturales.', 'Máscaras', 90000, 'Mascara-6.jpg', 'mostrar-mascaras', false],
    ];

    public function run(): void
    {
        foreach (self::PRODUCTOS as [$nombre, $descripcion, $categoria, $precio, $imagen, $ruta, $destacado]) {
            Producto::create([
                'nombre' => $nombre,
                'descripcion' => $descripcion,
                'categoria' => $categoria,
                'precio' => $precio,
                'imagen' => $imagen,
                'ruta_detalle' => $ruta,
                'destacado' => $destacado,
            ]);
        }
    }
}
