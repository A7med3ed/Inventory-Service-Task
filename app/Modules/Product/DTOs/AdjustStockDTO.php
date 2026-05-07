<?php

namespace App\Modules\Product\DTOs;

use App\Modules\Core\DTOs\BaseDTO;

class AdjustStockDTO extends BaseDTO
{
    public readonly int $delta;

    public function __construct(
        public readonly string $action,
        public readonly int    $quantity,
    ) {
        $this->delta = ($action === 'increment' ? $quantity : -$quantity) ;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            action:   $data['action'],
            quantity: (int) $data['quantity'],
        );
    }
}
