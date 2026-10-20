<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use App\Enums\UserRole;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Account Information')->schema([
                    TextInput::make('name')->required()->maxLength(255),
                    TextInput::make('email')->email()->required()->unique(ignoreRecord: true)->maxLength(255),
                    Select::make('role')
                        ->options(collect(UserRole::cases())->mapWithKeys(fn ($role) => [
                            $role->value => $role->label()
                        ]))
                        ->required(),
                ])->columns(2),
                Section::make('Delivery Profile')->schema([
                    TextInput::make('phone')->tel()->maxLength(50),
                    TextInput::make('address')->maxLength(255),
                    TextInput::make('city')->maxLength(100),
                    TextInput::make('index')->maxLength(20),
                ])->columns(2),
            ]);
    }
}
