<?php

namespace App\Modules\Product\Services;

use App\Modules\Core\Services\BaseService;
use App\Modules\Product\Contracts\ProductRepositoryInterface;
use App\Modules\Product\Contracts\ProductServiceInterface;
use App\Modules\Product\DTOs\AdjustStockDTO;
use App\Modules\Product\DTOs\CreateProductDTO;
use App\Modules\Product\DTOs\UpdateProductDTO;
use App\Modules\Product\Events\StockBelowThreshold;
use App\Modules\Product\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductService extends BaseService implements ProductServiceInterface
{
    public function __construct(ProductRepositoryInterface $repository)
    {
        parent::__construct($repository);

    }

    public function createFromDTO(CreateProductDTO $dto): Product
    {
        return $this->repository->create($dto->toArray());
    }

    public function updateFromDTO(Product $product, UpdateProductDTO $dto): Product
    {
        return $this->repository->update($product, $dto->toArray());
    }

    public function adjustStock(Product $product, AdjustStockDTO $dto): Product
    {
        $repository = $this->repository;
        $product    = $repository->adjustStock($product, $dto->delta);

        if ($product->isLowStock()) {
            event(new StockBelowThreshold($product));
        }

        return $product;
    }

    public function lowStock(): Collection
    {
        $repository = $this->repository;
        return $repository->lowStock();
    }
}
