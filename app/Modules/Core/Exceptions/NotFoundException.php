<?php

namespace App\Modules\Core\Exceptions;

use Exception;

class NotFoundException extends Exception
{
    public function __construct(string $resource = 'Resource', string $id = '')
    {
        parent::__construct("{$resource} not found" . ($id ? ": {$id}" : ''));
    }
}
