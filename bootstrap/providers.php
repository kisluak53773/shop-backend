<?php

declare(strict_types=1);

return [
    App\Product\Providers\ProductServiceProvider::class,
    App\Category\Providers\CategoryServiceProvider::class,
    App\Providers\AppServiceProvider::class,
    App\Providers\Filament\AdminPanelProvider::class,
    App\User\Providers\UserServiceProvider::class,
];
