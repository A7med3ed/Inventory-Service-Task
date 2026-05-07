<?php

namespace App\Modules\Product\Contracts;

use App\Modules\Core\Contracts\RepositoryInterface;
use App\Modules\Product\Models\Product;
use Illuminate\Database\Eloquent\Collection;

interface ProductRepositoryInterface extends RepositoryInterface
{
    public function adjustStock(Product $product, int $delta): Product;
    public function lowStock(): Collection;
}
