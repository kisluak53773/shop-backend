<?php

declare(strict_types=1);

namespace App\Product\Providers;

use Illuminate\Support\ServiceProvider;
use App\Product\Services\Interfaces\ProductServiceInterface;
use App\Product\Services\ProductService;
use App\Product\Repositories\Interfaces\ProductRepositoryInterface;
use App\Product\Repositories\ProductRepository;

class ProductServiceProvider extends ServiceProvider
{
    /**
     * All of the container bindings that should be registered.
     *
     * @var array
     */
    public $bindings = [
        ProductServiceInterface::class => ProductService::class,
        ProductRepositoryInterface::class => ProductRepository::class,
    ];
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
