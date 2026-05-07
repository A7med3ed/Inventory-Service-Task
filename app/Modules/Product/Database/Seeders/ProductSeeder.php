<?php

namespace App\Modules\Product\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Product\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Normal products
        Product::factory()->count(20)->create();

        // Low stock products
        Product::factory()
            ->count(5)
            ->lowStock()
            ->create();

        // Inactive products
        Product::factory()
            ->count(3)
            ->inactive()
            ->create();
    }
}
