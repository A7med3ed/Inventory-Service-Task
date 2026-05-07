<?php

namespace App\Modules\Product\Contracts;

use App\Modules\Core\Contracts\ServiceInterface;
use App\Modules\Product\DTOs\AdjustStockDTO;
use App\Modules\Product\DTOs\CreateProductDTO;
use App\Modules\Product\DTOs\UpdateProductDTO;
use App\Modules\Product\Models\Product;
use Illuminate\Database\Eloquent\Collection;

interface ProductServiceInterface extends ServiceInterface
{
    public function createFromDTO(CreateProductDTO $dto): Product;
    public function updateFromDTO(Product $product, UpdateProductDTO $dto): Product;
    public function adjustStock(Product $product, AdjustStockDTO $dto): Product;
    public function lowStock(): Collection;
}
