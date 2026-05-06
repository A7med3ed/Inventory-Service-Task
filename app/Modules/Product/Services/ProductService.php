<?php

namespace App\Modules\Product\Services;

use App\Modules\Product\Events\StockBelowThreshold;
use App\Modules\Product\Contracts\ProductServiceInterface;
use App\Modules\Product\Models\Product;
use App\Modules\Product\Contracts\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class ProductService implements ProductServiceInterface
{
    private const CACHE_TTL = 300;
    private const CACHE_TAG = 'products';

    public function __construct(
        private readonly ProductRepositoryInterface $repository,
    ) {}

    public function list(int $perPage = 15): LengthAwarePaginator
    {
        return Cache::tags([self::CACHE_TAG])->remember(
            "products:list:{$perPage}",
            self::CACHE_TTL,
            fn () => $this->repository->paginate($perPage)
        );
    }

    public function findById(string $id): ?Product
    {
        return $this->repository->findById($id);
    }

    public function create(array $data): Product
    {
        $product = $this->repository->create($data);
        $this->flushCache();
        return $product;
    }

    public function update(Product $product, array $data): Product
    {
        $product = $this->repository->update($product, $data);
        $this->flushCache();
        return $product;
    }

    public function delete(Product $product): void
    {
        $this->repository->softDelete($product);
        $this->flushCache();
    }

    public function adjustStock(Product $product, int $delta): Product
    {
        $product = $this->repository->adjustStock($product, $delta);
        $this->flushCache();

        if ($product->isLowStock()) {
            event(new StockBelowThreshold($product));
        }

        return $product;
    }

    public function lowStock(): Collection
    {
        return $this->repository->lowStock();
    }

    private function flushCache(): void
    {
        Cache::tags([self::CACHE_TAG])->flush();
    }
}
