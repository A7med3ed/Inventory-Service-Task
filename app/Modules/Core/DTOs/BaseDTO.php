<?php

namespace App\Modules\Core\DTOs;

abstract class BaseDTO
{
    public function toArray(): array
    {
        return array_filter(
            get_object_vars($this),
            fn ($value) => $value !== null
        );
    }
}
