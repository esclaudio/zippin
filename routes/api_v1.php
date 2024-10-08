<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\UserAddressController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => [
    'name' => 'Zippin Ecommerce v1',
    'now' => now(),
]);

Route::post('auth/login', [AuthController::class, 'login']);

Route::get('products', [ProductController::class, 'index']);
Route::get('products/{product:slug}', [ProductController::class, 'show']);

Route::middleware('auth:api')->group(function () {
    Route::post('auth/refresh', [AuthController::class, 'refresh']);

    Route::get('addresses', [UserAddressController::class, 'index']);

    Route::get('orders', [OrderController::class, 'index']);
    Route::post('orders', [OrderController::class, 'store']);
    Route::get('orders/{order}', [OrderController::class, 'show']);
});
