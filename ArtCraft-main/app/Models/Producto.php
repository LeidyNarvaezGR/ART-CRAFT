<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

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
        return asset('imagenes/'.$this->imagen);
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
