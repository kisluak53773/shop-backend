<?php

declare(strict_types=1);

namespace App\Cart\Providers;

use Illuminate\Support\ServiceProvider;
use App\Cart\Services\Interfaces\CartServiceInterface;
use App\Cart\Services\CartService;

class CartServiceProvider extends ServiceProvider
{
    /**
     * All of the container bindings that should be registered.
     *
     * @var array
     */
    public $bindings = [
        CartServiceInterface::class => CartService::class,
    ];

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        //
    }
}
