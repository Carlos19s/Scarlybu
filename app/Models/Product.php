<?php

namespace App\Models;

use App\Traits\Auditable;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use Auditable, HasFactory, SoftDeletes;

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

    /**
     * Get the full URL of the product image.
     */
    public function getImagenUrlAttribute(): ?string
    {
        if (! $this->imagen) {
            return null;
        }

        // If it's already a full URL (e.g. Cloudinary), return as-is
        if (str_starts_with($this->imagen, 'http')) {
            return $this->imagen;
        }

        return asset('storage/'.$this->imagen);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get all promotions for this product.
     */
    public function promociones()
    {
        return $this->hasMany(Promocion::class);
    }

    /**
     * Get the active promotion for this product.
     */
    public function getPromocionActivaAttribute(): ?Promocion
    {
        $today = now()->toDateString();

        if ($this->relationLoaded('promociones')) {
            return $this->promociones->filter(function ($promo) use ($today) {
                $inicio = $promo->fecha_inicio instanceof \DateTimeInterface ? $promo->fecha_inicio->format('Y-m-d') : $promo->fecha_inicio;
                $fin = $promo->fecha_fin instanceof \DateTimeInterface ? $promo->fecha_fin->format('Y-m-d') : $promo->fecha_fin;
                return $inicio <= $today && $fin >= $today;
            })->first();
        }

        return $this->promociones()
            ->where('fecha_inicio', '<=', $today)
            ->where('fecha_fin', '>=', $today)
            ->first();
    }

    /**
     * Get the current effective price of the product (takes promotion into account).
     */
    public function getPrecioActualAttribute(): float
    {
        $promocion = $this->promocion_activa;

        return $promocion ? (float) $promocion->precio_promocion : (float) $this->precio_venta;
    }

    /**
     * Determine if the product currently has an active promotion.
     */
    public function getTienePromocionAttribute(): bool
    {
        return ! is_null($this->promocion_activa);
    }

    /**
     * Get the discount percentage for the active promotion.
     */
    public function getPorcentajeDescuentoAttribute(): int
    {
        if ($this->tiene_promocion && $this->precio_venta > 0) {
            return (int) round((1 - ($this->precio_actual / $this->precio_venta)) * 100);
        }

        return 0;
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
