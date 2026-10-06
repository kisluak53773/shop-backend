<?php

declare(strict_types=1);

namespace App\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['name', 'description', 'price','stock_quantity'])]
class Product extends Model
{
    use SoftDeletes;
    public function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'stock_quantity' => 'integer',
        ];
    }

    public function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }

    public function primaryImage(): HasOne
    {
        return $this->hasOne(Image::class)->where('is_primary', true);
    }
}
