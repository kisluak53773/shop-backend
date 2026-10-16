<?php

declare(strict_types=1);

namespace App\User\Providers;

use Illuminate\Support\ServiceProvider;
use App\User\Services\AuthService;
use App\User\Services\Interfaces\AuthServiceInterface;

class UserServiceProvider extends ServiceProvider
{
    /**
    * All of the container bindings that should be registered.
    *
    * @var array
    */
    public $bindings = [
        AuthServiceInterface::class => AuthService::class,
    ];

    public function boot(): void {}
}
