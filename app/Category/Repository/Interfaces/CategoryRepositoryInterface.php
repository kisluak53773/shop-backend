<?php

declare(strict_types=1);

namespace App\Category\Repository\Interfaces;

use Illuminate\Support\Collection;
use App\Category\DTO\ListCategoriesDto;
use App\Category\DTO\CategoryTreeDto;

interface CategoryRepositoryInterface
{
    public function flat(ListCategoriesDto $dto): Collection;

    public function tree(CategoryTreeDto $dto): Collection;
}