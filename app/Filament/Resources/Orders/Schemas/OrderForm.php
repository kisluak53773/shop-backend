<?php

declare(strict_types=1);

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Schemas\Schema;
use App\Enums\OrderStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Order Status')
                    ->schema([
                        Select::make('status')
                            ->options(collect(OrderStatus::cases())->mapWithKeys(fn (OrderStatus $status) => [
                                $status->value => $status->label()
                            ]))
                            ->required()
                            ->default(OrderStatus::PENDING_PAYMENT->value),
                    ]),
                Section::make('Delivery Details')
                    ->schema([
                        TextInput::make('customer_name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('customer_email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        TextInput::make('customer_phone')
                            ->tel()
                            ->maxLength(50),
                        TextInput::make('customer_address')
                            ->maxLength(255),
                        TextInput::make('customer_city')
                            ->maxLength(100),
                        TextInput::make('customer_index')
                            ->maxLength(20),
                        Textarea::make('note')
                            ->maxLength(1000)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
