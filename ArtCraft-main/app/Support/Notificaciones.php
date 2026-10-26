<?php

namespace App\Support;

use Illuminate\Support\Carbon;

class Notificaciones
{
    /** Máximo de avisos que se conservan en la sesión. */
    private const MAXIMO = 20;

    /**
     * Guarda un aviso generado por una acción real de la persona.
     * No se crean notificaciones de ejemplo ni automáticas.
     */
    public static function registrar(string $tipo, string $titulo, string $detalle, ?string $url = null): void
    {
        $items = session('notificaciones', []);

        array_unshift($items, [
            'id' => $tipo.'-'.now()->getTimestampMs().'-'.substr(md5(uniqid('', true)), 0, 6),
            'tipo' => $tipo,
            'titulo' => $titulo,
            'detalle' => $detalle,
            'fecha' => now()->toIso8601String(),
            'url' => $url,
        ]);

        session(['notificaciones' => array_slice($items, 0, self::MAXIMO)]);
    }

    /** @return array<int, array<string, mixed>> */
    public static function todas(): array
    {
        $items = session('notificaciones', []);
        $leidas = session('notificaciones_leidas', []);

        $items = array_map(function (array $item) use ($leidas): array {
            $item['fecha'] = Carbon::parse($item['fecha']);
            $item['leida'] = in_array($item['id'], $leidas, true);

            return $item;
        }, $items);

        usort($items, fn (array $a, array $b) => $b['fecha']->getTimestamp() <=> $a['fecha']->getTimestamp());

        return $items;
    }

    public static function pendientes(): int
    {
        return count(array_filter(self::todas(), fn (array $n) => ! $n['leida']));
    }

    /** Elimina los avisos guardados en la sesión actual. */
    public static function limpiar(): void
    {
        session()->forget(['notificaciones', 'notificaciones_leidas']);
    }
}
