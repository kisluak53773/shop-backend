<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Category\Models\Category;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (string $operation, $state, callable $set): void {
                        if ($operation === 'create') {
                            $set('slug', Str::slug((string) $state));
                        }
                    }),
                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Textarea::make('description')
                    ->columnSpanFull()
                    ->rows(4)
                    ->maxLength(1000),
                Select::make('parent_id')
                    ->label('Parent Category')
                    ->options(function (?Category $record): array {
                        $query = Category::query()->orderBy('title');

                        if ($record instanceof Category) {
                            $query->whereNotIn('id', $record->selfAndDescendantIds());
                        }

                        return $query->pluck('title', 'id')->all();
                    })
                    ->searchable()
                    ->placeholder('Select a parent category')
                    ->helperText('Leave blank to create a top-level category'),
                FileUpload::make('image_path')
                    ->label('Category Image')
                    ->image()
                    ->disk('s3')
                    ->directory('categories/images')
                    ->visibility('public')
                    ->imagePreviewHeight('200')
                    ->downloadable()
                    ->openable()
                    ->afterStateHydrated(function (FileUpload $component, ?Category $record): void {
                        if ($record && $record->image) {
                            $component->state($record->image->path);
                        }
                    })
                    ->saveRelationshipsUsing(function (?Category $record, $state): void {
                        if (!$record) {
                            return;
                        }

                        if ($record->image && $record->image->path !== $state) {
                            $record->image->delete();
                        }

                        if ($state) {
                            $record->image()->create([
                                'path'       => $state,
                                'is_primary' => true,
                            ]);
                        }
                    })
                    ->helperText('Upload an image for the category'),
            ]);
    }
}
