<?php

declare(strict_types=1);

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        unset($data['total_amount']);
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['total_amount'] = 0;
        return $data;
    }

    protected function afterSave(): void
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
