<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'Laravel Modular Architecture',
        'version' => '1.0.0',
        'modules' => [
            'User',
            'Product',
        ],
    ]);
});
