<?php

declare(strict_types=1);

namespace App\Order\Http\Resources;

use Brick\Money\Money;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'number' => $this->number,
            'status' => $this->status->value,
            'status_label' => $this->status->label(),
            'total' => $this->moneyPayload($this->total_amount),
            'customer' => [
                'name' => $this->customer_name,
                'email' => $this->customer_email,
                'phone' => $this->customer_phone,
                'address' => $this->customer_address,
                'city' => $this->customer_city,
                'index' => $this->customer_index,
            ],
            'note' => $this->note,
            'payment_method' => PaymentMethodResource::make($this->whenLoaded('paymentMethod')),
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
            'created_at' => $this->created_at?->toIso8601String(),
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