<?php

declare(strict_types=1);

namespace App\Category\Providers;

use Illuminate\Support\ServiceProvider;
use App\Category\Services\Interfaces\CategoryServiceInterface;
use App\Category\Services\CategoryService;
use App\Category\Repository\Interfaces\CategoryRepositoryInterface;
use App\Category\Repository\CategoryRepository;

class CategoryServiceProvider extends ServiceProvider
{
    /**
     * All of the container bindings that should be registered.
     *
     * @var array
     */
    public $bindings = [
        CategoryRepositoryInterface::class => CategoryRepository::class,
        CategoryServiceInterface::class => CategoryService::class,
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
