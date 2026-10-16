<?php

declare(strict_types=1);

namespace App\Category\DTO;

readonly class ListCategoriesDto
{
    public function __construct(
        public ?string $search = null,
        public ?int $parentId = null,
        public bool $onlyRoots = false,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            search: $data['search'] ?? null,
            parentId: isset($data['parentId']) ? (int) $data['parentId'] : null,
            onlyRoots: filter_var($data['onlyRoots'] ?? false, FILTER_VALIDATE_BOOL),
        );
    }
}
