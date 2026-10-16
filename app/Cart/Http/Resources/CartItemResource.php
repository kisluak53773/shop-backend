<?php

declare(strict_types=1);

namespace App\Cart\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use App\Product\Http\Resources\ProductResource;
use Brick\Money\Money;

/** @mixin \App\Cart\Models\CartItem */
class CartItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $unitPrice = $this->product->price_amount;
        $lineTotal = $unitPrice->multipliedBy($this->quantity);

        $available    = (int) $this->product->stock_quantity;
        $isOutOfStock = $available <= 0;
        $hasShortage  = $this->quantity > $available;

        return [
            'id' => $this->id,
            'productId' => $this->product_id,
            'quantity' => $this->quantity,
            'unit_price' => $this->moneyPayload($unitPrice),
            'subtotal'   => $this->moneyPayload($lineTotal),
            'stock' => [
                'available' => $available,
                'isOutOfStock' => $isOutOfStock,
                'hasShortage' => $hasShortage,
            ],
            'product' => ProductResource::make($this->product),
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
