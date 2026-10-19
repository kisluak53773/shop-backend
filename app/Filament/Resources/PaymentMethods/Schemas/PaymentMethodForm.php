<?php

declare(strict_types=1);

namespace App\Filament\Resources\PaymentMethods\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;

class PaymentMethodForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('code')
                    ->label('Code')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->helperText('Unique identifier, e.g., cod, stripe, paypal'),
                Textarea::make('description')
                    ->label('Description')
                    ->columnSpanFull()
                    ->maxLength(1000),
            ]);
    }
}
