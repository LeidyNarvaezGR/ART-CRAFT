<?php

namespace App\Support;

use Carbon\Carbon;

class Notificaciones
{
    /** Máximo de notificaciones guardadas por sesión. */
    private const LIMITE = 20;

    /**
     * Registra una novedad real (carrito, subida de producto, mensaje, etc.).
     * Se guarda en la sesión del usuario y aparece en /notificaciones.
     */
    public static function agregar(string $tipo, string $titulo, string $detalle, ?string $url = null): void
    {
        $items = session('notificaciones', []);

        array_unshift($items, [
            'id' => uniqid($tipo.'-', true),
            'tipo' => $tipo,
            'titulo' => $titulo,
            'detalle' => $detalle,
            'fecha' => now()->toIso8601String(),
            'url' => $url,
        ]);

        session(['notificaciones' => array_slice($items, 0, self::LIMITE)]);
    }

    /**
     * Notificaciones reales de la sesión: primero las sin leer y, dentro de
     * cada grupo, de más reciente a más antigua.
     *
     * @return array<int, array{id: string, tipo: string, titulo: string, detalle: string, fecha: Carbon, url: string|null, leida: bool}>
     */
    public static function todas(): array
    {
        $items = session('notificaciones', []);
        $leidas = session('notificaciones_leidas', []);

        foreach ($items as &$item) {
            $item['fecha'] = Carbon::parse($item['fecha']);
            $item['leida'] = in_array($item['id'], $leidas, true);
        }
        unset($item);

        usort($items, function (array $a, array $b) {
            if ($a['leida'] !== $b['leida']) {
                return $a['leida'] ? 1 : -1;
            }

            return $b['fecha']->getTimestamp() <=> $a['fecha']->getTimestamp();
        });

        return $items;
    }

    public static function pendientes(): int
    {
        return count(array_filter(self::todas(), fn (array $n) => ! $n['leida']));
    }
}
