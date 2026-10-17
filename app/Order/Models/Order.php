<?php

declare(strict_types=1);

namespace App\Order\Models;

use App\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use App\Enums\OrderStatus;
use App\Casts\MoneyCast;

#[Fillable([
    'number',
    'user_id',
    'payment_method_id',
    'customer_name',
    'customer_email',
    'customer_phone',
    'customer_address',
    'customer_city',
    'customer_index',
    'note',
    'status',
    'total_amount',
    'currency',
])]
class Order extends Model
{
    public function casts(): array
    {
        return [
            'status' => OrderStatus::class,
            'total_amount' => MoneyCast::class . ':currency',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}