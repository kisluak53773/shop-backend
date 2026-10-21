<?php

declare(strict_types=1);

namespace App\Casts;

use Brick\Money\Money;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Database\Eloquent\SerializesCastableAttributes;
use InvalidArgumentException;

class MoneyCast implements CastsAttributes, SerializesCastableAttributes
{
    public function __construct(private readonly ?string $currencyColumn = null) {}

    public function get(Model $model, string $key, mixed $value, array $attributes): ?Money
    {
        if ($value === null) {
            return null;
        }

        $currency = $this->resolveCurrency($model, $attributes);

        return Money::ofMinor((int) $value, $currency);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): ?int
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof Money) {
            $expected = $this->resolveCurrency($model, $attributes);

            if ($value->getCurrency()->getCurrencyCode() !== $expected) {
                throw new InvalidArgumentException(
                    "Currency mismatch: got {$value->getCurrency()->getCurrencyCode()}, expected {$expected}.",
                );
            }

            return $value->getMinorAmount()->toInt();
        }

        if (is_int($value)) {
            return $value;
        }

        throw new InvalidArgumentException(
            'Value must be a Money instance or integer minor units.',
        );
    }

    private function resolveCurrency(Model $model, array $attributes): string
    {
        if ($this->currencyColumn !== null && isset($attributes[$this->currencyColumn])) {
            return (string) $attributes[$this->currencyColumn];
        }

        return (string) config('shop.currency', 'USD');
    }

    public function serialize(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof Money) {
            return $value->getMinorAmount()->toInt();
        }

        return $value;
    }
}
