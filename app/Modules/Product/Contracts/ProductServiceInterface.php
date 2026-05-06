<?php

namespace App\Modules\Product\Contracts;

use App\Modules\Product\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ProductServiceInterface
{
    public function list(int $perPage = 15): LengthAwarePaginator;

    public function findById(string $id): ?Product;

    public function create(array $data): Product;

    public function update(Product $product, array $data): Product;

    public function delete(Product $product): void;

    public function adjustStock(Product $product, int $delta): Product;

    public function lowStock(): Collection;
}
