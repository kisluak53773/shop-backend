<?php

declare(strict_types=1);

namespace App\Filament\Resources\Orders\Schemas;

use App\Enums\OrderStatus;
use App\Product\Models\Product;
use Brick\Money\Money;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;

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
                    ])
                    ->columnSpanFull(),
                Section::make('Order Items')
                    ->columnSpanFull()
                    ->schema([
                        Repeater::make('items')
                            ->relationship('items')
                            ->label('Products')
                            ->addActionLabel('Add Product')
                            ->live()
                            ->afterStateUpdated(function ($state, Set $set) {
                                self::recalculateTotal($state, $set);
                            })
                            ->columns(12)
                            ->schema([
                                TextEntry::make('image')
                                    ->hiddenLabel()
                                    ->columnSpan(1)
                                    ->html()
                                    ->state(function (Get $get) {
                                        $productId = $get('product_id');
                                        if (!$productId) return '';

                                        $product = Product::find($productId);
                                        $image = $product?->primaryImage;
                                        if (!$image) return '';

                                        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
                                        $disk = Storage::disk('s3');
                                        $url = $disk->url($image->path);

                                        return "<img src='{$url}' class='w-10 h-10 object-cover rounded' alt='{$product->name}' />";
                                    }),
                                Select::make('product_id')
                                    ->label('Product')
                                    ->options(Product::query()->pluck('name', 'id'))
                                    ->searchable()
                                    ->required()
                                    ->live()
                                    ->columnSpan(5)
                                    ->disabled(fn ($record) => $record !== null)
                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                        if ($state) {
                                            $product = Product::find($state);
                                            if ($product) {
                                                $set('product_name', $product->name);
                                                $set('unit_price_amount', $product->price_amount->getMinorAmount()->toInt());
                                                $set('currency', $product->price_currency);
                                                
                                                // Recalculate item total for this row
                                                $set('total_amount', $product->price_amount->getMinorAmount()->toInt() * ((int) $get('quantity') ?? 1));

                                                $items = $get('../../items') ?? [];
                                                self::recalculateTotal($items, $set, '../../total_amount_display');
                                            }
                                        }
                                    }),
                                TextInput::make('quantity')
                                    ->label('Quantity')
                                    ->numeric()
                                    ->required()
                                    ->default(1)
                                    ->live(onBlur: true)
                                    ->columnSpan(2)
                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                        $price = (int) $get('unit_price_amount');
                                        $set('total_amount', $price * (int) $state);

                                        $items = $get('../../items') ?? [];
                                        self::recalculateTotal($items, $set, '../../total_amount_display');
                                    }),
                                TextEntry::make('unit_price_display')
                                    ->label('Unit Price')
                                    ->columnSpan(2)
                                    ->state(function (Get $get) {
                                        $cents = (int) $get('unit_price_amount');
                                        $currency = $get('currency') ?? config('shop.currency', 'USD');
                                        return Money::ofMinor($cents, $currency)->formatToLocale('en_US');
                                    }),

                                TextEntry::make('subtotal_display')
                                    ->label('Subtotal')
                                    ->columnSpan(2)
                                    ->state(function (Get $get) {
                                        $cents = (int) $get('unit_price_amount');
                                        $qty = (int) $get('quantity');
                                        $currency = $get('currency') ?? config('shop.currency', 'USD');
                                        return Money::ofMinor($cents * $qty, $currency)->formatToLocale('en_US');
                                    }),
                                Hidden::make('product_name'),
                                Hidden::make('unit_price_amount'),
                                Hidden::make('currency'),
                                Hidden::make('total_amount'),
                            ]),
                        TextInput::make('total_amount_display')
                            ->label('Total Amount')
                            ->disabled()
                            ->dehydrated(false)
                            ->formatStateUsing(function ($record, Get $get) {
                                $items = $get('items') ?? [];
                                $total = 0;
                                $currency = config('shop.currency', 'USD');
                                foreach ($items as $item) {
                                    if (is_array($item)) {
                                        $total += ((int) ($item['unit_price_amount'] ?? 0)) * ((int) ($item['quantity'] ?? 1));
                                        if (isset($item['currency'])) {
                                            $currency = $item['currency'];
                                        }
                                    }
                                }
                                if ($total === 0 && $record && $record->total_amount) {
                                    return $record->total_amount->formatToLocale('en_US');
                                }
                                return Money::ofMinor($total, $currency)->formatToLocale('en_US');
                            }),
                    ]),
                Section::make('Delivery Details')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('customer_name')->required()->maxLength(255),
                        TextInput::make('customer_email')->email()->required()->maxLength(255),
                        TextInput::make('customer_phone')->tel()->maxLength(50),
                        TextInput::make('customer_address')->maxLength(255),
                        TextInput::make('customer_city')->maxLength(100),
                        TextInput::make('customer_index')->maxLength(20),
                        Textarea::make('note')->maxLength(1000)->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    private static function recalculateTotal($items, Set $set, string $targetField = 'total_amount_display'): void
    {
        if (!is_array($items)) {
            $items = [];
        }

        $total = 0;
        $currency = config('shop.currency', 'USD');

        foreach ($items as $item) {
            if (is_array($item)) {
                $price = (int) ($item['unit_price_amount'] ?? 0);
                $qty = (int) ($item['quantity'] ?? 0);
                $total += $price * $qty;
                if (isset($item['currency'])) {
                    $currency = $item['currency'];
                }
            }
        }

        $set($targetField, Money::ofMinor($total, $currency)->formatToLocale('en_US'));
    }
}