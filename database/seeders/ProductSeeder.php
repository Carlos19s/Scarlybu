<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Seed products with predictable, consistent image paths.
     *
     * Images are stored locally in storage/app/public/productos/.
     * Each developer should place the shared images manually in that folder.
     */
    public function run(): void
    {
        $products = [
            [
                'nombre' => 'Gorra Gallo',
                'slug' => 'gorra-roja-gallo',
                'descripcion' => 'Una hermosa gorra roja con bordado de gallo',
                'imagen' => 'productos/gorra-gallo-roja.webp',
                'category_slug' => 'gorras',
                'precio_compra' => 7.50,
                'precio_venta' => 15.00,
                'iva_porcentaje' => 15.00,
                'stock' => 20,
                'stock_minimo' => 5,
                'activo' => true,
            ],

        ];

        foreach ($products as $productData) {
            $categorySlug = $productData['category_slug'];
            unset($productData['category_slug']);

            $category = Category::where('slug', $categorySlug)->first();

            if (! $category) {
                $this->command?->warn("Categoría '{$categorySlug}' no encontrada, omitiendo producto '{$productData['nombre']}'.");

                continue;
            }

            $productData['category_id'] = $category->id;

            Product::firstOrCreate(
                ['slug' => $productData['slug']],
                $productData
            );
        }
    }
}
