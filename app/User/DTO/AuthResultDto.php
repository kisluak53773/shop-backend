<?php

declare(strict_types=1);

namespace App\User\DTO;

use App\Cart\Models\Cart;
use App\User\Models\User;

readonly class AuthResultDto
{
    public function __construct(
        public User $user,
        public Cart $cart,
        public string $token,
    ) {}
}
