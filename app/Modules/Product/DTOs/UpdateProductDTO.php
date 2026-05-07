<?php

namespace App\Modules\Product\DTOs;

use App\Modules\Core\DTOs\BaseDTO;
use App\Modules\Product\Enums\ProductStatus;

class UpdateProductDTO extends BaseDTO
{
    public function __construct(
        public readonly ?string        $sku                 = null,
        public readonly ?string        $name                = null,
        public readonly ?float         $price               = null,
        public readonly ?string        $description         = null,
        public readonly ?int           $stock_quantity      = null,
        public readonly ?int           $low_stock_threshold = null,
        public readonly ?ProductStatus $status              = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            sku:                 $data['sku'] ?? null,
            name:                $data['name'] ?? null,
            price:               isset($data['price']) ? (float) $data['price'] : null,
            description:         $data['description'] ?? null,
            stock_quantity:      isset($data['stock_quantity']) ? (int) $data['stock_quantity'] : null,
            low_stock_threshold: isset($data['low_stock_threshold']) ? (int) $data['low_stock_threshold'] : null,
            status:              isset($data['status']) ? ProductStatus::from($data['status']) : null,
        );
    }

    public function toArray(): array
    {
        $data = [];

        if ($this->sku !== null)                 $data['sku']                 = $this->sku;
        if ($this->name !== null)                $data['name']                = $this->name;
        if ($this->price !== null)               $data['price']               = $this->price;
        if ($this->description !== null)         $data['description']         = $this->description;
        if ($this->stock_quantity !== null)      $data['stock_quantity']      = $this->stock_quantity;
        if ($this->low_stock_threshold !== null) $data['low_stock_threshold'] = $this->low_stock_threshold;
        if ($this->status !== null)              $data['status']              = $this->status->value;

        return $data;
    }
}
