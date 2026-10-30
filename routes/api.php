<?php

use App\Http\Controllers\Api\CurrencyController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductPriceController;
use Illuminate\Support\Facades\Route;

// Todas las rutas requieren API key y tienen límite de solicitudes
Route::middleware(['api.key', 'throttle:60,1'])->group(function () {
    Route::apiResource('products', ProductController::class);

    Route::get('products/{product}/prices', [ProductPriceController::class, 'index']);
    Route::post('products/{product}/prices', [ProductPriceController::class, 'store']);

    Route::get('currencies', [CurrencyController::class, 'index']);
});
