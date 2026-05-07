<?php

namespace App\Modules\Product\Repositories;

use App\Modules\Core\Repositories\BaseRepository;
use App\Modules\Core\Traits\CachableRepository;
use App\Modules\Product\Contracts\ProductRepositoryInterface;
use App\Modules\Product\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    use CachableRepository;

    protected int $cacheTtl = 300;

    public function __construct()
    {
        parent::__construct(new Product());
    }

    protected function getCacheTag(): string
    {
        return 'products';
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->remember(
            "products:list:{$perPage}",
            fn () => $this->model->newQuery()->latest()->paginate($perPage)
        );
    }

    public function create(array $data): \Illuminate\Database\Eloquent\Model
    {
        $product = $this->model->newQuery()->create($data);
        $this->flushCache();
        return $product;
    }

    public function update(\Illuminate\Database\Eloquent\Model $model, array $data): \Illuminate\Database\Eloquent\Model
    {
        $model->update($data);
        $this->flushCache();
        return $model->fresh();
    }

    public function delete(\Illuminate\Database\Eloquent\Model $model): void
    {
        $model->delete();
        $this->flushCache();
    }

    public function adjustStock(Product $product, int $delta): Product
    {
        $result = DB::transaction(function () use ($product, $delta): Product {
            $locked = Product::lockForUpdate()->find($product->id);
            $newQty = max(0, $locked->stock_quantity + $delta);
            $locked->update(['stock_quantity' => $newQty]);
            return $locked->fresh();
        });

        $this->flushCache();
        return $result;
    }

    public function lowStock(): Collection
    {
        return $this->model->newQuery()->lowStock()->get();
    }
}
