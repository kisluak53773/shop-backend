<?php

declare(strict_types=1);

namespace App\Cart\Services\Interfaces;

use App\Cart\DTO\AddCartItemDto;
use App\Cart\DTO\RemoveCartItemDto;
use App\Cart\DTO\UpdateCartItemDto;
use App\Cart\Models\Cart;
use App\User\Models\User;

interface CartServiceInterface
{
    public function resolve(?User $user, ?string $token): Cart;

    public function attachToUser(User $user, ?string $token): Cart;

    public function addItem(Cart $cart, AddCartItemDto $dto): Cart;

    public function updateItem(Cart $cart, UpdateCartItemDto $dto): Cart;

    public function removeItem(Cart $cart, RemoveCartItemDto $dto): Cart;

    public function clear(Cart $cart): Cart;

    public function createForGuest(): Cart;

    public function assertCheckoutable(Cart $cart): void;
}