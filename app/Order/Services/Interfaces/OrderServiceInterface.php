<?php

declare(strict_types=1);

namespace App\Order\Services\Interfaces;

use App\Cart\Models\Cart;
use App\Order\DTO\CreateOrderDto;
use App\Order\Models\Order;
use App\User\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Order\DTO\ListOrdersDto;

interface OrderServiceInterface
{
    public function createOrder(?User $user, CreateOrderDto $dto, Cart $cart): Order;

    public function paginateUserOrders(ListOrdersDto $dto): LengthAwarePaginator;
}
