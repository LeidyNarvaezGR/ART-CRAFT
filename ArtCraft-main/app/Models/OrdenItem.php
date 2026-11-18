<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrdenItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'orden_id',
        'producto_id',
        'nombre',
        'precio',
        'cantidad',
        'subtotal',
    ];

    protected function casts(): array
    {
        return [
            'precio' => 'integer',
            'cantidad' => 'integer',
            'subtotal' => 'integer',
        ];
    }

    public function orden(): BelongsTo
    {
        return $this->belongsTo(Orden::class, 'orden_id');
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    public function getSubtotalFormateadoAttribute(): string
    {
        return '$ '.number_format($this->subtotal, 0, ',', '.');
    }
}
