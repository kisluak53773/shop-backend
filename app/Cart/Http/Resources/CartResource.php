<?php

declare(strict_types=1);

namespace App\Cart\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use Brick\Money\Money;

/** @mixin \App\Cart\Models\Cart */
class CartResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $items = $this->whenLoaded('items');
        $currency = $items->first()?->product->price_currency ?? config('shop.currency', 'USD');
        $subtotal = $items
            ->filter(fn ($item) => ((int) $item->product->stock_quantity) > 0)
            ->reduce(
                fn (Money $carry, $item) => $carry->plus(
                    $item->product->price_amount->multipliedBy($item->quantity),
                ),
                Money::ofMinor(0, $currency),
            );
        
        return [
            'id' => $this->id,
            'token' => $this->token,
            'items' => CartItemResource::collection($items),
            'itemsCount' => $items->count(),
            'subtotal' => $this->moneyPayload($subtotal),
            'has_shortage' => $items->contains(
                fn ($item) => $item->quantity > ((int) $item->product->stock_quantity),
            ),
            'updatedAt' => $this->updated_at,
        ];
    }

    /**
     * @return array{amount: int, currency: string, formatted: string}
     */
    private function moneyPayload(Money $money): array
    {
        return [
            'amount' => $money->getMinorAmount()->toInt(),
            'currency' => $money->getCurrency()->getCurrencyCode(),
            'formatted' => $money->formatToLocale('en_US'),
        ];
    }
}