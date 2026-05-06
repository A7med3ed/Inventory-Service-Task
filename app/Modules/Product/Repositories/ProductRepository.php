<?php

namespace App\Modules\Product\Repositories;

use App\Modules\Product\Contracts\ProductRepositoryInterface;
use App\Modules\Product\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ProductRepository implements ProductRepositoryInterface
{
    public function __construct(
        protected Product $model
    ) {}

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->query()->latest()->paginate($perPage);
    }

    public function findById(string $id): ?Product
    {
        return $this->model->find($id);
    }

    public function create(array $data): Product
    {
        return $this->model->create($data);
    }

    public function update(Product $product, array $data): Product
    {
        $product->update($data);
        return $product->fresh();
    }

    public function softDelete(Product $product): void
    {
        $product->delete();
    }

    public function adjustStock(Product $product, int $delta): Product
    {
        return DB::transaction(function () use ($product, $delta): Product {
            $product = $this->model->lockForUpdate()->find($product->id);
            $newQty  = max(0, $product->stock_quantity + $delta);
            $product->update(['stock_quantity' => $newQty]);
            return $product->fresh();
        });
    }

    public function lowStock(): Collection
    {
        return $this->model->query()->lowStock()->get();
    }
}
