<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Promocion;
use Illuminate\Database\Seeder;

class PromocionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $today = now();

        // Seed sample promotions for demo products
        $gorra = Product::where('slug', 'gorra-roja-gallo')->first();
        $pantalla = Product::where('slug', 'pantalla')->first();

        if ($gorra) {
            Promocion::updateOrCreate(
                ['product_id' => $gorra->id],
                [
                    'precio_promocion' => 10.00, // Original: 15.00 (33% off)
                    'fecha_inicio' => $today->copy()->subDay()->toDateString(),
                    'fecha_fin' => $today->copy()->addDays(7)->toDateString(),
                ]
            );
        }

        if ($pantalla) {
            Promocion::updateOrCreate(
                ['product_id' => $pantalla->id],
                [
                    'precio_promocion' => 150.00, // Original: 200.00 (25% off)
                    'fecha_inicio' => $today->copy()->subDay()->toDateString(),
                    'fecha_fin' => $today->copy()->addDays(7)->toDateString(),
                ]
            );
        }
    }
}
