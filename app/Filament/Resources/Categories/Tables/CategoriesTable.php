<?php

declare(strict_types=1);

namespace App\Filament\Resources\Categories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image.path')
                    ->label('Category Image')
                    ->disk('s3')
                    ->visibility('public')
                    ->imageSize(60)
                    ->circular(),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('parent.title')
                    ->label('Parent')
                    ->placeholder('-'),
                TextColumn::make('products_count')
                    ->label('Products')
                    ->counts('products')
                    ->sortable(),
                TextColumn::make('sort_order')
                    ->label('Sort Order')
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->filters([
                SelectFilter::make('parent_id')
                    ->label('Parent Category')
                    ->relationship('parent', 'title')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
