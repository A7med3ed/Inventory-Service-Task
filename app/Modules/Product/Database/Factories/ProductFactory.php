<?php

namespace App\Modules\Product\Database\Factories;

use App\Modules\Product\Enums\ProductStatus;
use App\Modules\Product\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'sku'                 => strtoupper($this->faker->unique()->bothify('SKU-####-???')),
            'name'                => $this->faker->words(3, true),
            'description'         => $this->faker->sentence(),
            'price'               => $this->faker->randomFloat(2, 1, 999),
            'stock_quantity'      => $this->faker->numberBetween(11, 100),
            'low_stock_threshold' => 10,
            'status'              => ProductStatus::Active->value,
        ];
    }

    public function lowStock(): static
    {
        return $this->state(fn () => [
            'stock_quantity'      => $this->faker->numberBetween(0, 9),
            'low_stock_threshold' => 10,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn () => [
            'status' => ProductStatus::Inactive->value,
        ]);
    }
}
