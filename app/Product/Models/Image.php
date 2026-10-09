<?php

declare(strict_types=1);

namespace App\Product\Models;

use App\Category\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'imageable_id', 'imageable_type',
    'path', 'mobile_path',
    'is_primary', 'sort_order', 'alt_text',
])]
class Image extends Model
{
    public function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_primary' => 'boolean',
        ];
    }

    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopePrimary(Builder $query): Builder
    {
        return $query->where('is_primary', true);
    }

    protected static function booted(): void
    {
        static::deleting(function (Image $image): void {
            Storage::disk('s3')->delete($image->path);

            if ($image->mobile_path) {
                Storage::disk('s3')->delete($image->mobile_path);
            }
        });

        static::updating(function (Image $image): void {
            $oldPath = $image->getOriginal('path');
            $oldMobilePath = $image->getOriginal('mobile_path');

            $toDeleteArray = array_filter([
                $oldPath !== $image->path ? $oldPath : null,
                $oldMobilePath !== $image->mobile_path ? $oldMobilePath : null,
            ]);

            if (!empty($toDeleteArray)) {
                Storage::disk('s3')->delete($toDeleteArray);
            }
        });

        static::creating(function (Image $image): void {
            if (!isset($image->is_primary) && $image->imageable_type === Category::class) {
                $image->is_primary = true;
            }
        });
    }
}
