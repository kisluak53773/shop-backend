<?php

declare(strict_types= 1);

namespace App\Product\DTO;

readonly class TrendingProductsDto
{
    public function __construct(
        public int $page = 1,
        public int $perPage = 10,
        public string $order = 'asc'
    )
    {}

    public static function fromArray(array $data): self
    {
        return new self(
          page: (int) ($data['page'] ?? 1),
          perPage: (int) ($data['perPage'] ?? 10),
          order: $data['order'] ?? 'asc',
        );
    }
}