<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nombre',
        'slug',
        'descripcion',
        'imagen',
        'category_id',
        'precio_compra',
        'precio_venta',
        'iva_porcentaje',
        'stock',
        'stock_minimo',
        'fecha_caducidad',
        'activo',
    ];

    protected $casts = [
        'precio_compra' => 'decimal:2',
        'precio_venta' => 'decimal:2',
        'iva_porcentaje' => 'decimal:2',
        'fecha_caducidad' => 'date',
        'activo' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Clear storefront caches when a product changes.
     */
    protected static function booted(): void
    {
        $clearCache = function ($product) {
            cache()->forget('home_featured_products');
            cache()->forget("related_products_{$product->id}");
            if ($product->category_id) {
                cache()->forget("category_{$product->category_id}_product_count");
                // Also clear parent category count if this is a subcategory
                $parent = Category::find($product->category_id);
                if ($parent?->parent_id) {
                    cache()->forget("category_{$parent->parent_id}_product_count");
                }
            }
        };

        static::saved($clearCache);
        static::deleted($clearCache);
    }
}
