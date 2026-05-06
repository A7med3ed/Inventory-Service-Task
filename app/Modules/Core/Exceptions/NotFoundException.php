<?php

namespace App\Modules\Core\Exceptions;

use Exception;

class NotFoundException extends Exception
{
    public function __construct(string $message = "Resource not found", int $code = 404)
    {
        parent::__construct($message, $code);
    }

    public function render()
    {
        return response()->json([
            'error' => true,
            'message' => $this->getMessage(),
        ], $this->getCode());
    }
}
