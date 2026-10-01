<?php

declare(strict_types=1);

use App\Providers\AppServiceProvider;
use App\User\Providers\UserServiceProvider;

return [
    AppServiceProvider::class,
    UserServiceProvider::class,
];
