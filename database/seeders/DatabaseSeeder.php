<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Product\Database\Seeders\ProductSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Run Product Seeder
        $this->call([
            ProductSeeder::class,
        ]);
    }
}
