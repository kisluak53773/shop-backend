<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\User\Http\Controllers\AuthController;
use App\Product\Http\Controllers\ProductController;

//Auth routes
Route::prefix('auth')->group(function (): void {
    Route::post('/register', [AuthController::class, 'register'])->name('user.register');
    Route::post('/login', [AuthController::class, 'login'])->name('user.login');

    Route::middleware('auth:sanctum')->group(function (): void {
        Route::get('/me', [AuthController::class, 'me'])->name('user.me');
        Route::post('/logout', [AuthController::class, 'logout'])->name('user.logout');
    });
});

//Product routes
Route::prefix('product')->group(function (): void {
    Route::get('/', [ProductController::class, 'index'])->name('product.index');
});
