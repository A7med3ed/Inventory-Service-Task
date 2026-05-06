<?php

namespace App\Modules\Product\Enums;

enum ProductStatus: string
{
    case Active       = 'active';
    case Inactive     = 'inactive';
    case Discontinued = 'discontinued';
}
