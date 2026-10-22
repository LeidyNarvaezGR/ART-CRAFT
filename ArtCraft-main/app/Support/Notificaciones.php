<?php

namespace App\Support;

use App\Models\Producto;

class Notificaciones
{
    public static function todas(): array
    {
        $items = [];

        foreach (Producto::orderByDesc('id')->take(3)->get() as $producto) {
            $items[] = [
                'id' => 'producto-'.$producto->id,
                'tipo' => 'producto',
                'titulo' => 'Nuevo producto publicado',
                'detalle' => '"'.$producto->nombre.'" ya está disponible en '.$producto->categoria.'.',
                'fecha' => $producto->created_at ?? now(),
                'url' => $producto->url,
            ];
        }

        $carrito = session('carrito', []);
        if ($carrito !== []) {
            $items[] = [
                'id' => 'carrito',
                'tipo' => 'carrito',
                'titulo' => 'Tu carrito te espera',
                'detalle' => 'Tienes '.array_sum($carrito).' artículos guardados en el carrito de compras.',
                'fecha' => now(),
                'url' => route('carrito'),
            ];
        }

        $items[] = [
            'id' => 'pedido-1024',
            'tipo' => 'pedido',
            'titulo' => 'Pedido despachado',
            'detalle' => 'Tu pedido #1024 salió del taller y va en camino a Popayán.',
            'fecha' => now()->subDay(),
            'url' => null,
        ];

        $items[] = [
            'id' => 'mensaje-1',
            'tipo' => 'mensaje',
            'titulo' => 'María Gómez respondió tu mensaje',
            'detalle' => 'Gracias por escribirnos: te contestamos por correo en menos de 24 horas.',
            'fecha' => now()->subDays(2),
            'url' => route('contacto'),
        ];

        $items[] = [
            'id' => 'valoracion-1',
            'tipo' => 'valoracion',
            'titulo' => 'Nueva valoración: 5 estrellas',
            'detalle' => 'Una compradora valoró "Mochila wayuu" con la máxima calificación.',
            'fecha' => now()->subDays(3),
            'url' => null,
        ];

        $leidas = session('notificaciones_leidas', []);

        foreach ($items as &$item) {
            $item['leida'] = in_array($item['id'], $leidas, true);
        }
        unset($item);

        usort($items, fn (array $a, array $b) => $b['fecha']->getTimestamp() <=> $a['fecha']->getTimestamp());

        return $items;
    }

    public static function pendientes(): int
    {
        return count(array_filter(self::todas(), fn (array $n) => ! $n['leida']));
    }
}
