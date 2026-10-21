<?php

declare(strict_types=1);

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['number'] = 'ORD-' . strtoupper((string) Str::ulid());
        $data['currency'] = config('shop.currency', 'USD');
        $data['total_amount'] = 0;

        return $data;
    }

    protected function afterCreate(): void
    {
        $order = $this->record;
        $total = 0;

        foreach ($order->items as $item) {
            $itemTotal = $item->unit_price_amount->getMinorAmount()->toInt() * $item->quantity;
            $item->update(['total_amount' => $itemTotal]);

            $total += $itemTotal;
        }

        $order->update(['total_amount' => $total]);
    }
}
