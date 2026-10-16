<?php

declare(strict_types=1);

namespace App\Cart\DTO;

readonly class UpdateCartItemDto
{
    public function __construct(
        public int $itemId,
        public int $quantity,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            itemId: (int) $data['itemId'],
            quantity: (int) $data['quantity'],
        );
    }
}
