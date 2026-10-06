<?php

declare(strict_types=1);

namespace App\Product\DTO;

readonly class ListProductsDto
{
    public function __construct(
        public int $page = 1,
        public int $perPage = 10,
        public ?string $search = null,
        public string $sort = 'created_at',
        public string $order  = 'desc',
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            page: (int) ($data['page'] ?? 1),
            perPage: (int) ($data['perPage'] ?? 10),
            search: $data['search'] ?? null,
            sort: $data['sort'] ?? 'created_at',
            order: $data['order'] ?? 'desc',
        );
    }
}
