<?php

declare(strict_types=1);

namespace App\Filament\Resources\Products\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Brick\Money\Money;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Repeater::make('images')
                ->relationship('images')
                ->label('Gallery')
                ->addActionLabel('Add Image')
                ->collapsible()
                ->cloneable()
                ->reorderable()
                ->orderColumn('sort_order')
                ->defaultItems(0)
                ->grid(1)
                ->itemLabel(
                    function (array $state): ?string {
                        $path = $state['path'] ?? null;

                        if ($path instanceof TemporaryUploadedFile) {
                            return $path->getClientOriginalName();
                        }

                        if (is_string($path) && !empty($path)) {
                            return basename($path);
                        }

                        return 'New Image';
                    }
                )
                ->schema([
                    FileUpload::make('path')
                        ->label('Image')
                        ->image()
                        ->disk('s3')
                        ->directory('products/images/desktop')
                        ->visibility('public')
                        ->imagePreviewHeight('200')
                        ->downloadable()
                        ->openable()
                        ->required(),
                    FileUpload::make('mobile_path')
                        ->label('Mobile Image')
                        ->image()
                        ->disk('s3')
                        ->directory('products/images/mobile')
                        ->visibility('public')
                        ->imagePreviewHeight('200')
                        ->downloadable()
                        ->openable()
                        ->helperText('Will be used if uploaded on mobile devices'),
                    Toggle::make('is_primary')
                        ->label('Is image primary?')
                        ->default(false)
                        ->helperText('Will be used as the primary image for the product'),
                    TextInput::make('alt_text')
                        ->label('Alt text')
                        ->maxLength(125),
                ]),
                TextInput::make('name')
                  ->required()
                  ->maxLength(255),
                Textarea::make('description')
                    ->columnSpanFull()
                    ->maxLength(1000),
                TextInput::make('price_amount')
                    ->label('Price')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->step('0.01')
                    ->prefix('$')
                    ->helperText('Enter the price in dollars, e.g. 19.99')
                    ->formatStateUsing(function ($state): ?string {
                        if ($state === null) {
                            return null;
                        }

                        if ($state instanceof Money) {
                            return number_format(
                                $state->getAmount()->toFloat(),
                                2,
                                '.',
                                '',
                            );
                        }

                        return number_format($state / 100, 2, '.', '');
                    })
                    ->dehydrateStateUsing(function ($state): ?int {
                        if ($state === null || $state === '') {
                            return null;
                        }

                        return (int) round((float) $state * 100);
                    }),
                TextInput::make('stock_quantity')
                    ->required()
                    ->integer()
                    ->minValue(0),
                Select::make('categories')
                    ->relationship('categories', 'title')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->label('Categories')
                    ->placeholder('Select categories')
                    ->helperText('Select categories for the product'),
            ]);
    }
}
