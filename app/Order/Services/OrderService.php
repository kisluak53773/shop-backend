<?php

declare(strict_types=1);

namespace App\Order\Services;

use App\Order\Services\Interfaces\OrderServiceInterface;
use App\Cart\Models\Cart;
use App\Order\DTO\CreateOrderDto;
use App\Order\Models\Order;
use App\Product\Models\Product;
use App\User\Models\User;
use Illuminate\Support\Str;
use App\Enums\OrderStatus;
use Brick\Money\Money;
use App\Order\Repositories\Interfaces\OrderRepositoryInterface;
use App\Order\DTO\ListOrdersDto;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Mail;
use App\Order\Mail\OrderConfirmationEmail;

class OrderService implements OrderServiceInterface
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
    ) {}

    public function createOrder(?User $user, CreateOrderDto $dto, Cart $cart): Order
    {
        $cart->load('items.product');
        $productIds = $cart->items->pluck('product_id')->unique();
        $products = Product::whereIn('id', $productIds)->lockForUpdate()->get()->keyBy('id');
        $currency = config('shop.currency', 'USD');

        $order = Order::create([
            'number' => 'ORD-' . strtoupper((string) Str::ulid()),
            'user_id' => $user?->id,
            'payment_method_id' => $dto->paymentMethodId,
            'customer_name' => $dto->customerName ?? $user?->name,
            'customer_email' => $dto->customerEmail ?? $user?->email,
            'customer_phone' => $dto->customerPhone ?? $user?->phone,
            'customer_address' => $dto->customerAddress ?? $user?->address,
            'customer_city' => $dto->customerCity ?? $user?->city,
            'customer_index' => $dto->customerIndex ?? $user?->index,
            'note' => $dto->note,
            'status' => OrderStatus::PENDING_PAYMENT->value,
            'total_amount' => 0,
            'currency' => $currency,
        ]);

        $total = Money::ofMinor(0, $currency);

        foreach ($cart->items as $cartItem) {
            $product = $products[$cartItem->product_id];
            $unitPrice = $product->price_amount;
            $lineTotal = $unitPrice->multipliedBy($cartItem->quantity);

            $order->items()->create([
                'product_id' => $product->id,
                'product_name' => $product->name,
                'unit_price_amount' => $unitPrice->getMinorAmount()->toInt(),
                'currency' => $currency,
                'quantity' => $cartItem->quantity,
                'total_amount' => $lineTotal->getMinorAmount()->toInt(),
            ]);

            $product->decrement('stock_quantity', $cartItem->quantity);
            $total = $total->plus($lineTotal);
        }

        $order->update([
            'total_amount' => $total->getMinorAmount()->toInt(),
        ]);

        $order->load('items', 'paymentMethod');

        Mail::to($order->customer_email)->send(new OrderConfirmationEmail($order));

        return $order;
    }

    public function paginateUserOrders(ListOrdersDto $dto): LengthAwarePaginator
    {
        return $this->orderRepository->list($dto);
    }
}