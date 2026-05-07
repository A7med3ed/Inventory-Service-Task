<?php

namespace App\Modules\Product\Enums;

enum ProductStatus: string
{
    case Active       = 'active';
    case Inactive     = 'inactive';
    case Discontinued = 'discontinued';

    public function label(): string
    {
        return match($this) {
            self::Active       => 'Active',
            self::Inactive     => 'Inactive',
            self::Discontinued => 'Discontinued',
        };
    }

    public function isActive(): bool
    {
        return $this === self::Active;
    }
}
