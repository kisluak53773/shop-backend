<?php

declare(strict_types=1);

namespace App\Filament\Resources\Products\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                  ->required()
                  ->maxLength(255),
                Textarea::make('description')
                    ->columnSpanFull()
                    ->maxLength(1000),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('stock_quantity')
                    ->required()
                    ->integer()
                    ->minValue(0),

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
            ]);
    }
}
