<?php

declare(strict_types=1);

namespace App\Cart\DTO;

readonly class AddCartItemDto
{
    public function __construct(
        public int $productId,
        public int $quantity
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            productId: (int) $data['productId'],
            quantity: (int) $data['quantity'],
        );
    }
}
