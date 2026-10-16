<?php

declare(strict_types=1);

namespace App\User\Actions;

use App\User\DTO\LoginUserDto;
use App\User\DTO\AuthResultDto;
use App\User\Services\Interfaces\AuthServiceInterface;
use App\Cart\Services\Interfaces\CartServiceInterface;
use Illuminate\Support\Facades\DB;

class LoginUserAction
{
    public function __construct(
        private AuthServiceInterface $authService,
        private CartServiceInterface $cartService,
    ) {}

    public function execute(LoginUserDto $dto, ?string $cartToken): AuthResultDto
    {
        return DB::transaction(function () use ($dto, $cartToken) {
            $user = $this->authService->loginUser($dto);
            $cart = $this->cartService->attachToUser($user, $cartToken);
            $token = $user->createToken('access_token')->plainTextToken;

            return new AuthResultDto($user, $cart, $token);
        });
    }
}
