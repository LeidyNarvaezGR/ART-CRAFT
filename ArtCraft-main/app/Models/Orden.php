<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Orden extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario_id',
        'nombre_cliente',
        'email',
        'telefono',
        'direccion',
        'ciudad',
        'referencia',
        'subtotal',
        'envio',
        'total',
        'moneda',
        'estado',
        'wompi_transaction_id',
        'wompi_status',
        'metodo_pago',
        'respuesta_wompi',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'integer',
            'envio' => 'integer',
            'total' => 'integer',
            'respuesta_wompi' => 'array',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrdenItem::class, 'orden_id');
    }

    public function getTotalFormateadoAttribute(): string
    {
        return '$ '.number_format($this->total, 0, ',', '.');
    }
}
