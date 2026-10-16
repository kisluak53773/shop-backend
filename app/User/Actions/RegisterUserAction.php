<?php

declare(strict_types=1);

namespace App\User\Actions;

use App\User\Services\Interfaces\AuthServiceInterface;
use App\User\DTO\AuthResultDto;
use App\Cart\Services\Interfaces\CartServiceInterface;
use App\User\DTO\RegisterUserDto;
use Illuminate\Support\Facades\DB;

class RegisterUserAction
{
    public function __construct(
        private AuthServiceInterface $authService,
        private CartServiceInterface $cartService,
    ) {}

    public function execute(RegisterUserDto $dto, ?string $cartToken): AuthResultDto
    {
        return DB::transaction(function () use ($dto, $cartToken) {
            $user = $this->authService->registerUser($dto);
            $cart = $this->cartService->attachToUser($user, $cartToken);
            $token = $user->createToken('access_token')->plainTextToken;

            return new AuthResultDto($user, $cart, $token);
        });
    }
}
