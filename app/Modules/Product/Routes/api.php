<?php

use App\Modules\Product\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:api')->group(function () {
    Route::get('/products/low-stock',        [ProductController::class, 'lowStock']);
    Route::get('/products',                  [ProductController::class, 'index']);
    Route::post('/products',                 [ProductController::class, 'store']);
    Route::get('/products/{product}',        [ProductController::class, 'show']);
    Route::put('/products/{product}',        [ProductController::class, 'update']);
    Route::delete('/products/{product}',     [ProductController::class, 'destroy']);
    Route::post('/products/{product}/stock', [ProductController::class, 'adjustStock']);
});
