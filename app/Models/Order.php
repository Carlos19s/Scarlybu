<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $fillable = [
        'user_id','numero_pedido','cliente_nombre','cliente_telefono',
        'cliente_correo','cliente_direccion','cliente_documento',
        'estado','total','total_iva','es_vip','notas',
    ];

    protected $casts = [
        'total'     => 'decimal:2',
        'total_iva' => 'decimal:2',
        'es_vip'    => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $order) {
            $ultimo = self::max('id') ?? 0;
            $order->numero_pedido = 'ORD-' . str_pad($ultimo + 1, 6, '0', STR_PAD_LEFT);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
}