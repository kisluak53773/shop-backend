<?php

declare(strict_types=1);

namespace App\Cart\DTO;

readonly class RemoveCartItemDto
{
    public function __construct(
        public int $itemId,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            itemId: (int) $data['itemId'],
        );
    }
}
