<?php

declare(strict_types=1);

namespace App\Order\Actions;

use App\Cart\Models\Cart;
use App\Order\DTO\CreateOrderDto;
use App\Order\Models\Order;
use App\User\Models\User;
use App\Order\Services\Interfaces\OrderServiceInterface;
use App\Cart\Services\Interfaces\CartServiceInterface;
use Illuminate\Support\Facades\DB;
use App\Order\Exceptions\EmptyCartException;
use App\Order\Exceptions\NoCartException;
use Illuminate\Database\Eloquent\Builder;

class CreateOrderAction
{
    public function __construct(
        private readonly OrderServiceInterface $orderService,
        private readonly CartServiceInterface $cartService,
    ) {}

    public function execute(?User $user, CreateOrderDto $dto, ?string $cartToken): Order
    {
        if ($user === null && $cartToken === null) {
            throw new NoCartException();
        }

        return DB::transaction(function () use ($user, $dto, $cartToken) {
            $cart = Cart::query()
                ->when(isset($user), function (Builder $query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->when($cartToken !== null && $cartToken !== '', function (Builder $query) use ($cartToken) {
                    $query->where('token', $cartToken);
                })
                ->whereNull('user_id')
                ->lockForUpdate()
                ->first();

            if (!isset($cart) || $cart->items()->count() === 0) {
                throw new EmptyCartException();
            }

            $this->cartService->assertCheckoutable($cart);
            
            $order = $this->orderService->createOrder($user, $dto, $cart);

            $this->cartService->clear($cart);

            return $order;
        });
    }
}