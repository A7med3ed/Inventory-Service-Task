<?php

namespace App\Modules\Product\DTOs;

use App\Modules\Core\DTOs\BaseDTO;
use App\Modules\Product\Enums\ProductStatus;

class CreateProductDTO extends BaseDTO
{
    public function __construct(
        public readonly string        $sku,
        public readonly string        $name,
        public readonly float         $price,
        public readonly ?string       $description         = null,
        public readonly int           $stock_quantity      = 0,
        public readonly int           $low_stock_threshold = 10,
        public readonly ProductStatus $status              = ProductStatus::Active,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            sku:                 $data['sku'],
            name:                $data['name'],
            price:               (float) $data['price'],
            description:         $data['description'] ?? null,
            stock_quantity:      (int) ($data['stock_quantity'] ?? 0),
            low_stock_threshold: (int) ($data['low_stock_threshold'] ?? 10),
            status:              isset($data['status'])
                ? ProductStatus::from($data['status'])
                : ProductStatus::Active,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'sku'                 => $this->sku,
            'name'                => $this->name,
            'price'               => $this->price,
            'description'         => $this->description,
            'stock_quantity'      => $this->stock_quantity,
            'low_stock_threshold' => $this->low_stock_threshold,
            'status'              => $this->status->value,
        ], fn ($v) => $v !== null);
    }
}
