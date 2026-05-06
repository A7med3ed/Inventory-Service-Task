<?php

namespace App\Modules\Product\DTOs;

readonly class AdjustStockDTO
{
    public function __construct(
        public int $product_id,
        public int $quantity,
        public string $reason = 'manual_adjustment'
    ) {}

    public function toArray(): array
    {
        return [
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
            'reason' => $this->reason,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            product_id: (int) $data['product_id'],
            quantity: (int) $data['quantity'],
            reason: $data['reason'] ?? 'manual_adjustment'
        );
    }
}
