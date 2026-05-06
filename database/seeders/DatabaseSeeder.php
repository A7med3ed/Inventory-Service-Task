<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\User\Models\User;
use App\Modules\Product\Models\Category;
use App\Modules\Product\Models\Product;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Users
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);

        User::factory(10)->create();

        // Create Categories
        $electronics = Category::factory()->create([
            'name' => 'Electronics',
            'slug' => 'electronics',
            'description' => 'Electronic devices and accessories',
        ]);

        $clothing = Category::factory()->create([
            'name' => 'Clothing',
            'slug' => 'clothing',
            'description' => 'Apparel and fashion items',
        ]);

        $books = Category::factory()->create([
            'name' => 'Books',
            'slug' => 'books',
            'description' => 'Books and educational materials',
        ]);

        // Create Subcategories
        Category::factory()->create([
            'name' => 'Laptops',
            'slug' => 'laptops',
            'parent_id' => $electronics->id,
        ]);

        Category::factory()->create([
            'name' => 'Smartphones',
            'slug' => 'smartphones',
            'parent_id' => $electronics->id,
        ]);

        // Create Products
        Product::factory(20)->create([
            'category_id' => $electronics->id,
        ]);

        Product::factory(15)->create([
            'category_id' => $clothing->id,
        ]);

        Product::factory(10)->create([
            'category_id' => $books->id,
        ]);

        // Create some specific products
        Product::factory()->expensive()->count(5)->create();
        Product::factory()->outOfStock()->count(3)->create();
        Product::factory()->lowStock()->count(5)->create();
    }
}
