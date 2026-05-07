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
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ProductService extends BaseService implements ProductServiceInterface
{
    public function __construct(private readonly ProductRepositoryInterface $productRepository)
    {
        parent::__construct($productRepository);
    }

    private function asProductOrNull(?Model $model): ?Product
    {
        assert($model === null || $model instanceof Product);
        return $model;
    }

    public function list(int $perPage = 15): LengthAwarePaginator
    {
        return $this->productRepository->paginate($perPage);
    }

    public function findById(string $id): ?Product
    {
        return $this->asProductOrNull($this->productRepository->findById($id));
    }

    public function createFromDTO(CreateProductDTO $dto): Product
    {
        return $this->asProductOrNull($this->productRepository->create($dto->toArray()));
    }

    public function updateFromDTO(Product $product, UpdateProductDTO $dto): Product
    {
        return $this->asProductOrNull($this->productRepository->update($product, $dto->toArray()));
    }

    public function adjustStock(Product $product, AdjustStockDTO $dto): Product
    {
        $product = $this->productRepository->adjustStock($product, $dto->delta);

        if ($product->isLowStock()) {
            event(new StockBelowThreshold($product));
        }

        return $product;
    }

    public function delete(Model $model): void
    {
        $this->productRepository->delete($model);
    }

    public function update(Model $model, array $data): Product
    {
        return $this->asProductOrNull($this->productRepository->update($model, $data));
    }

    public function create(array $data): Product
    {
        return $this->asProductOrNull($this->productRepository->create($data));
    }

    public function lowStock(): Collection
    {
        return $this->productRepository->lowStock();
    }

}
