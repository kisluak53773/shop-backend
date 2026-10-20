<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\User\Http\Controllers\AuthController;
use App\Product\Http\Controllers\ProductController;
use App\Category\Http\Controllers\CategoryController;
use App\Cart\Http\Controllers\CartController;
use App\Order\Http\Controllers\OrderController;
use App\Order\Http\Controllers\PaymentMethodController;

//Auth routes
Route::prefix('auth')->group(function (): void {
    Route::post('/register', [AuthController::class, 'register'])->name('user.register');
    Route::post('/login', [AuthController::class, 'login'])->name('user.login');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('user.forgotPassword');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('user.resetPassword');

    Route::middleware('auth:sanctum')->group(function (): void {
        Route::get('/me', [AuthController::class, 'me'])->name('user.me');
        Route::post('/logout', [AuthController::class, 'logout'])->name('user.logout');
    });
});

Route::get('/payment-methods', [PaymentMethodController::class, 'index'])->name('payment.methods.index');

//Product routes
Route::prefix('product')->group(function (): void {
    Route::get('/', [ProductController::class, 'index'])->name('product.index');
});

//Category routes
Route::prefix('category')->group(function (): void {
    Route::get('/', [CategoryController::class, 'index'])->name('category.index');
    Route::get('/tree', [CategoryController::class, 'tree'])->name('category.tree');
});

//Cart routes
Route::prefix('cart')->middleware('cart')->group(function (): void {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::post('/item', [CartController::class, 'addItem'])->name('cart.addItem');
    Route::put('/item/{itemId}', [CartController::class, 'updateItem'])->name('cart.updateItem');
    Route::delete('/item/{itemId}', [CartController::class, 'removeItem'])->name('cart.removeItem');
    Route::delete('/', [CartController::class, 'clear'])->name('cart.clear');
});

//Order routes
Route::prefix('order')->group(function (): void {
    Route::get('/', [OrderController::class, 'index'])->name('order.index')->middleware('auth:sanctum');
    Route::post('/', [OrderController::class, 'store'])->name('order.store');
});
