<?php

namespace App\Modules\Core\Exceptions;

use Exception;

class ValidationException extends Exception
{
    public function __construct(string $message = 'Validation failed')
    {
        parent::__construct($message);
    }
}
