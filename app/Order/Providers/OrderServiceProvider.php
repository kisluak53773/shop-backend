<?php

declare(strict_types=1);

namespace App\Order\Providers;

use Illuminate\Support\ServiceProvider;
use App\Order\Services\Interfaces\OrderServiceInterface;
use App\Order\Services\OrderService;
use App\Order\Services\Interfaces\PaymentMethodServiceInterface;
use App\Order\Services\PaymentMethodService;
use App\Order\Repositories\Interfaces\OrderRepositoryInterface;
use App\Order\Repositories\OrderRepository;

class OrderServiceProvider extends ServiceProvider
{
    /**
     * All of the container bindings that should be registered.
     *
     * @var array
     */
    public $bindings = [
        OrderServiceInterface::class => OrderService::class,
        PaymentMethodServiceInterface::class => PaymentMethodService::class,
        OrderRepositoryInterface::class => OrderRepository::class,
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