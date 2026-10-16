<?php

declare(strict_types=1);

namespace App\Cart\Services;

use App\Cart\DTO\AddCartItemDto;
use App\Cart\DTO\RemoveCartItemDto;
use App\Cart\DTO\UpdateCartItemDto;
use App\Cart\Models\Cart;
use App\Cart\Services\Interfaces\CartServiceInterface;
use App\User\Models\User;
use App\Product\Models\Product;
use App\Cart\Exceptions\InsufficientStockException;

class CartService implements CartServiceInterface
{
    public function resolve(?User $user, ?string $token): Cart
    {
        if (isset($user)) {
            return Cart::firstOrCreate(['user_id' => $user->id]);
        }

        if ($token !== null && $token !== '') {
            $existing = Cart::where('token', $token)->whereNull('user_id')->first();

            if (isset($existing)) {
                return $existing;
            }
        }

        return $this->createForGuest();
    }

    public function attachToUser(User $user, ?string $token): Cart
    {
        $userCart = Cart::firstOrCreate(['user_id' => $user->id]);

        if ($token === null || $token === '') {
            return $userCart;
        }

        $guestCart = Cart::where('token', $token)
            ->whereNull('user_id')
            ->with('items.product')
            ->first();

        if (!isset($guestCart)) {
            return $userCart;
        }

        foreach ($guestCart->items as $guestItem) {
            $product = $guestItem->product;
            $available = $product->stock_quantity ?? 0;
            $existing = $userCart->items
                ->where('product_id', $guestItem->product_id)
                ->first();
            $combined = ($existing?->quantity ?? 0) + $guestItem->quantity;
            $caped = $available > 0 ? min($combined, $available) : $combined;

            if (isset($existing)) {
                $existing->update(['quantity' => $caped]);
            } else {
                $userCart->items()->create([
                    'product_id' => $guestItem->product_id,
                    'quantity' => $caped,
                ]);
            }
        }

        $guestCart->delete();
        $userCart->touch();

        return $userCart;
    }

    public function addItem(Cart $cart, AddCartItemDto $dto): Cart
    {
        $product = Product::findOrFail($dto->productId);
        $existing = $cart->items()
            ->where('product_id', $dto->productId)
            ->first();
        $targetQuantity = ($existing?->quantity ?? 0) + $dto->quantity;

        $this->guardStock($product, $targetQuantity);

        if (isset($existing)) {
            $existing->update(['quantity' => $targetQuantity]);
        } else {
            $cart->items()->create([
                'product_id' => $dto->productId,
                'quantity' => $targetQuantity,
            ]);
        }

        $cart->touch();

        return $cart->fresh('items.product.primaryImage');
    }

    public function updateItem(Cart $cart, UpdateCartItemDto $dto): Cart
    {
        $item = $cart->items()->with('product')->findOrFail($dto->itemId);

        $this->guardStock($item->product, $dto->quantity);

        $item->update(['quantity' => $dto->quantity]);
        $cart->touch();

        return $cart->fresh('items.product.primaryImage');
    }

    public function removeItem(Cart $cart, RemoveCartItemDto $dto): Cart
    {
        $cart->items()->where('id', $dto->itemId)->delete();
        $cart->touch();

        return $cart->fresh('items.product.primaryImage');
    }

    public function clear(Cart $cart): Cart
    {
        $cart->items()->delete();
        $cart->touch();

        return $cart->fresh();
    }

    public function assertCheckoutable(Cart $cart): void
    {
        $cart->loadMissing('items.product');

        foreach ($cart->items as $item) {
            $product = $item->product;

            $available = (int) $product->stock_quantity;

            if ($item->quantity > $available) {
                throw new InsufficientStockException(
                    $product,
                    $item->quantity,
                    $available,
                );
            }
        }
    }

    private function guardStock(Product $product, int $quantity): void
    {
        $available = (int) $product->stock_quantity;

        if ($quantity > $available) {
            throw new InsufficientStockException($product, $quantity, $available);
        }
    }

    public function createForGuest(): Cart
    {
        return Cart::create();
    }
}
