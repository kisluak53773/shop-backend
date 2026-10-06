<?php

declare(strict_types=1);

namespace App\Product\Repositories;

use App\Product\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Product\DTO\ListProductsDto;
use App\Product\Models\Product;

class ProductRepository implements ProductRepositoryInterface
{
    public function paginated(ListProductsDto $dto): LengthAwarePaginator
    {
        return Product::query()
            ->with('primaryImage')
            ->when(
                $dto->search && $dto->search !== '',
                fn($query) => $query->where('name', 'like', '%' . $dto->search . '%')
            )
            ->orderBy($dto->sort, $dto->order)
            ->paginate($dto->perPage, ['*'], 'page', $dto->page)
            ->withQueryString();
    }
}
