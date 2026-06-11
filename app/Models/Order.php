<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'numero_pedido',
        'cliente_nombre',
        'cliente_documento',
        'cliente_telefono',
        'cliente_correo',
        'cliente_direccion',
        'estado',
        'total',
        'total_iva',
        'es_vip',
        'notas',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'total_iva' => 'decimal:2',
        'es_vip' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
