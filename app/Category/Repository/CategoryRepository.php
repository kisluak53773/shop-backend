<?php

declare(strict_types=1);

namespace App\Category\Repository;

use App\Category\Repository\Interfaces\CategoryRepositoryInterface;
use App\Category\DTO\ListCategoriesDto;
use Illuminate\Support\Collection;
use App\Category\Models\Category;
use App\Category\DTO\CategoryTreeDto;
use Illuminate\Database\Eloquent\Builder;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function flat(ListCategoriesDto $dto): Collection
    {
        return Category::query()
            ->with('image')
            ->withCount('products')
            ->when(
                $dto->search !== null && $dto->search !== '',
                fn (Builder $query) => $query->where('title', 'like', '%' . $dto->search , '%')
            )
            ->when(
                $dto->onlyRoots,
                fn (Builder $query) => $query->whereNull('parent_id')
            )
            ->when(
                $dto->parentId !== null,
                fn (Builder $query) => $query->where('parent_id', $dto->parentId)
            )
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();
    }

    public function tree (CategoryTreeDto $dto): Collection
    {
        return Category::query()
            ->whereNull('parent_id')
            ->with(['image', 'childrenRecursive.image'])
            ->withCount('products')
            ->when(
                $dto->search !== null && $dto->search !== '',
                fn (Builder $query) => $query->where('title', 'like', '%' . $dto->search , '%')
            )
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();
    }
}