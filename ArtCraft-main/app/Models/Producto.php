<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    /** Imagenes disponibles en public/imagenes (se listan una sola vez). */
    private static ?array $imagenes = null;

    protected $fillable = [
        'nombre',
        'descripcion',
        'categoria',
        'precio',
        'imagen',
        'ruta_detalle',
        'destacado',
    ];

    protected function casts(): array
    {
        return [
            'precio' => 'integer',
            'destacado' => 'boolean',
        ];
    }

    public function getImagenUrlAttribute(): string
    {
        $archivo = $this->buscarImagen($this->imagen);

        return asset($archivo ? 'imagenes/'.$archivo : 'imagenes/sin-imagen.png');
    }

    /** Busca la imagen real del producto aunque el nombre cambie de mayusculas. */
    private function buscarImagen(?string $nombre): ?string
    {
        if (! $nombre) {
            return null;
        }

        if (self::$imagenes === null) {
            $directorio = public_path('imagenes');
            $archivos = is_dir($directorio) ? array_diff(scandir($directorio) ?: [], ['.', '..']) : [];

            self::$imagenes = [];

            foreach ($archivos as $archivo) {
                self::$imagenes[strtolower($archivo)] = $archivo;
            }
        }

        return self::$imagenes[strtolower($nombre)] ?? null;
    }

    public function getPrecioFormateadoAttribute(): string
    {
        return '$ '.number_format($this->precio, 0, ',', '.');
    }

    public function getUrlAttribute(): string
    {
        return route($this->ruta_detalle, $this);
    }

    public function scopeDestacados($query)
    {
        return $query->where('destacado', true);
    }

    public function scopeDeCategoria($query, string $categoria)
    {
        return $query->where('categoria', $categoria);
    }
}
