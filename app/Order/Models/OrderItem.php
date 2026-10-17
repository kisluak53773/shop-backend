<?php

declare(strict_types=1);

namespace App\Order\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Product\Models\Product;

#[Fillable([
    'order_id',
    'product_id',
    'product_name',
    'unit_price_amount',
    'currency',
    'quantity',
    'total_amount',
])]
class OrderItem extends Model
{
    public function casts(): array
    {
        return [
            'unit_price_amount' => MoneyCast::class . ':currency',
            'total_amount' => MoneyCast::class . ':currency',
            'quantity' => 'integer',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}