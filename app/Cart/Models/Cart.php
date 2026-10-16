<?php

declare(strict_types=1);

namespace App\Cart\Models;

use App\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

#[Fillable(['user_id', 'token'])]
class Cart extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function isGuest(): bool
    {
        return $this->user_id === null;
    }

    public static function booted(): void
    {
        static::creating(function (Cart $cart): void {
            if (!isset($cart->token)) {
                $cart->token = Str::uuid();
            }
        });
    }
}
