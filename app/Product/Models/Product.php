<?php

declare(strict_types=1);

namespace App\Product\Models;

use App\Category\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Casts\MoneyCast;

#[Fillable(['name', 'description', 'price_amount','stock_quantity'])]
class Product extends Model
{
    use SoftDeletes;
    public function casts(): array
    {
        return [
            'price_amount' => MoneyCast::class . ':price_currency',
            'price_currency' => 'string',
            'stock_quantity' => 'integer',
        ];
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function primaryImage(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable')->where('is_primary', true);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
}
