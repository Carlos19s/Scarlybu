<?php

namespace App\Models;

use App\Traits\Auditable;
use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /** @use HasFactory<CategoryFactory> */
    use Auditable, HasFactory;

    protected $fillable = [
        'nombre',
        'slug',
        'descripcion',
        'parent_id',
        'imagen',
        'activa',
    ];

    protected $casts = [
        'activa' => 'boolean',
    ];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function getAllCategoryIds(): array
    {
        return cache()->remember("category_{$this->id}_all_ids", 3600, function () {
            $ids = [$this->id];
            $childIds = self::where('parent_id', $this->id)->pluck('id')->toArray();

            return array_merge($ids, $childIds);
        });
    }

    public function getAllProductsCount(): int
    {
        return cache()->remember("category_{$this->id}_product_count", 1800, function () {
            return Product::whereIn('category_id', $this->getAllCategoryIds())
                ->where('activo', true)
                ->count();
        });
    }

    /**
     * Clear category caches when saving/updating.
     */
    protected static function booted(): void
    {
        static::saved(function () {
            cache()->forget('nav_categories');
            cache()->forget('home_categories');
            cache()->forget('home_featured_products');
            // Clear individual category caches
            foreach (self::all() as $cat) {
                cache()->forget("category_{$cat->id}_all_ids");
                cache()->forget("category_{$cat->id}_product_count");
            }
        });

        static::deleted(function () {
            cache()->forget('nav_categories');
            cache()->forget('home_categories');
            cache()->forget('home_featured_products');
        });
    }
}
