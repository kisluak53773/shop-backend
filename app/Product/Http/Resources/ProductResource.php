<?php

declare(strict_types=1);

namespace App\Product\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use App\Product\Http\Resources\ImageResource;
use Brick\Money\Money;

/** @mixin \App\Product\Models\Product */
class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->moneyPayload($this->price_amount),
            'primaryImage' => $this->whenLoaded(
                'primaryImage',
                fn() => $this->primaryImage
                ? ImageResource::make($this->primaryImage) : null
            ),
            'stockQuantity' => $this->stock_quantity,
            'createdAt' => $this->created_at,
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
