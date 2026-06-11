<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $table = 'products';
    protected $fillable = [
        'nombre','slug','descripcion','imagen','category_id',
        'precio_compra','precio_venta','iva_porcentaje',
        'stock','stock_minimo','fecha_caducidad','activo',
    ];

    protected $casts = [
        'precio_compra'   => 'decimal:2',
        'precio_venta'    => 'decimal:2',
        'iva_porcentaje'  => 'decimal:2',
        'activo'          => 'boolean',
        'fecha_caducidad' => 'date',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_id');
    }

    public function getPrecioConIvaAttribute(): float
    {
        return round($this->precio_venta * (1 + $this->iva_porcentaje / 100), 2);
    }

    public function getBajoStockAttribute(): bool
    {
        return $this->stock <= $this->stock_minimo;
    }
}