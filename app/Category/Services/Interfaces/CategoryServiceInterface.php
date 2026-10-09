<?php

declare(strict_types=1);

namespace App\Category\Services\Interfaces;

use Illuminate\Support\Collection;
use App\Category\DTO\ListCategoriesDto;
use App\Category\DTO\CategoryTreeDto;

interface CategoryServiceInterface
{
    public function list(ListCategoriesDto $dto): Collection;

    public function tree(CategoryTreeDto $dto): Collection;
}