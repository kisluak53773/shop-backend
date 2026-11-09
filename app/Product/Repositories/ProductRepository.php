<?php

declare(strict_types=1);

namespace App\Product\Repositories;

use App\Product\DTO\TrendingProductsDto;
use App\Product\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Product\DTO\ListProductsDto;
use App\Product\Models\Product;
use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Builder;

class ProductRepository implements ProductRepositoryInterface
{
    public function paginated(ListProductsDto $dto): LengthAwarePaginator
    {
        return Product::query()
            ->with('primaryImage')
            ->when(
                $dto->search && $dto->search !== '',
                fn(Builder $query) => $query->where('name', 'like', '%' . $dto->search . '%')
            )
            ->orderBy($dto->sort, $dto->order)
            ->paginate($dto->perPage, ['*'], 'page', $dto->page)
            ->withQueryString();
    }

    public function trending(TrendingProductsDto $dto): LengthAwarePaginator
    {
        return Product::query()
            ->withSum([
                'orderItems' => fn(Builder $query) => $query->whereHas(
                    'order',
                    fn(Builder $orderQuery) => $orderQuery->whereNotIn('status',[
                        OrderStatus::CANCELLED->value,
                        OrderStatus::REFUNDED->value,
                        OrderStatus::FAILED->value,
                        OrderStatus::DRAFT->value,
                    ])
                ),
            ],'quantity')
            ->orderBy('order_items_sum_quantity', $dto->order)
            ->orderBy('id', 'desc')
            ->with('primaryImage')
            ->paginate(
                perPage: $dto->perPage,
                page: $dto->page
            )
            ->withQueryString();
    }
}
