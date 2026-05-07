<?php

namespace App\Modules\Product\Models;

use App\Modules\Core\Traits\HasUuid;
use App\Modules\Product\Database\Factories\ProductFactory;
use App\Modules\Product\Enums\ProductStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, HasUuid, SoftDeletes;

    protected $fillable = [
        'sku',
        'name',
        'description',
        'price',
        'stock_quantity',
        'low_stock_threshold',
        'status',
    ];

    protected $casts = [
        'price'               => 'decimal:2',
        'stock_quantity'      => 'integer',
        'low_stock_threshold' => 'integer',
        'status'              => ProductStatus::class,
    ];

    protected static function newFactory(): ProductFactory
    {
        return ProductFactory::new();
    }

    public function scopeLowStock(Builder $query): Builder
    {
        return $query
            ->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
            ->where('status', ProductStatus::Active->value);
    }

    public function isLowStock(): bool
    {
        return $this->stock_quantity <= $this->low_stock_threshold;
    }
}
