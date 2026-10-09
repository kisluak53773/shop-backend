<?php

declare(strict_types=1);

namespace App\Category\DTO;

readonly class CategoryTreeDto
{
    public function __construct(
        public ?string $search = null,
    ){}

    public static function fromArray(array $data): self
    {
        return new self(
            search: $data['search'] ?? null,
        );
    }
}