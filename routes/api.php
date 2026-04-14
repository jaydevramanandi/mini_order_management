<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application.
|
*/

// Public routes - Authentication
Route::post('/auth/register', [App\Http\Controllers\AuthController::class, 'register']);
Route::post('/auth/login', [App\Http\Controllers\AuthController::class, 'login']);

// Protected routes - Require authentication
Route::middleware('auth:sanctum')->group(function () {
    
    // Auth routes
    Route::post('/auth/logout', [App\Http\Controllers\AuthController::class, 'logout']);
    Route::get('/auth/me', [App\Http\Controllers\AuthController::class, 'me']);

    // Product routes    
    Route::get('/products/{id}', [App\Http\Controllers\ProductController::class, 'show']);
    Route::get('/products/{filter?}', [App\Http\Controllers\ProductController::class, 'index'])->middleware('throttle:2,1');
    Route::post('/products', [App\Http\Controllers\ProductController::class, 'store']);
    Route::put('/products/{id}', [App\Http\Controllers\ProductController::class, 'update']);
    Route::delete('/products/{id}', [App\Http\Controllers\ProductController::class, 'destroy']);

    // Order routes
    Route::get('/orders', [App\Http\Controllers\OrderController::class, 'index']);
    Route::post('/orders', [App\Http\Controllers\OrderController::class, 'store']);
    Route::get('/orders/{id}', [App\Http\Controllers\OrderController::class, 'show']);
});