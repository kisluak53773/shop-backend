<?php

declare(strict_types=1);

namespace App\Category\Services;

use App\Category\DTO\ListCategoriesDto;
use App\Category\Services\Interfaces\CategoryServiceInterface;
use App\Category\Repository\Interfaces\CategoryRepositoryInterface;
use App\Category\DTO\CategoryTreeDto;
use Illuminate\Support\Collection;

class CategoryService implements CategoryServiceInterface
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository
    )
    {
    }

    public function list(ListCategoriesDto $dto): Collection
    {
        return $this->categoryRepository->flat($dto);
    }

    public function tree (CategoryTreeDto $dto): Collection
    {
        return $this->categoryRepository->tree($dto);
    }
}