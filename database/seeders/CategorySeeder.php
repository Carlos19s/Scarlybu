<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['nombre' => 'Gorras', 'descripcion' => 'Gorras de todos los estilos y marcas'],
            ['nombre' => 'Accesorios', 'descripcion' => 'Accesorios de moda para cualquier ocasión'],
            ['nombre' => 'Cosméticos', 'descripcion' => 'Productos de belleza y cuidado personal'],
            ['nombre' => 'Ropa', 'descripcion' => 'Ropa casual y de tendencia'],
            ['nombre' => 'Zapatos', 'descripcion' => 'Calzado para todos los gustos'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['slug' => Str::slug($category['nombre'])],
                array_merge($category, [
                    'slug' => Str::slug($category['nombre']),
                    'activa' => true,
                ])
            );
        }
    }
}
