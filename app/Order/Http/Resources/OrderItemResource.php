<?php

declare(strict_types=1);

namespace App\Order\Http\Resources;

use Brick\Money\Money;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'productId' => $this->product_id,
            'productName' => $this->product_name,
            'quantity' => $this->quantity,
            'unitPrice' => $this->moneyPayload($this->unit_price_amount),
            'total' => $this->moneyPayload($this->total_amount),
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
