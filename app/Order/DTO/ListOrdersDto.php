<?php

declare(strict_types=1);

namespace App\Order\DTO;

readonly class ListOrdersDto
{
    public function __construct(
        public int $userId,
        public int $page = 1,
        public int $perPage = 15,
        public ?string $status = null,
        public string $sort = 'created_at',
        public string $order = 'desc',
    ) {}

    public static function fromArray(array $data, int $userId): self
    {
        return new self(
            userId: $userId,
            page: (int) ($data['page'] ?? 1),
            perPage: (int) ($data['perPage'] ?? 15),
            status: $data['status'] ?? null,
            sort: $data['sort'] ?? 'created_at',
            order: $data['order'] ?? 'desc',
        );
    }
}
