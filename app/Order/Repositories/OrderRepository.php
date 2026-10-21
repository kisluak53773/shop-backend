<?php

declare(strict_types=1);

namespace App\Order\Repositories;

use App\Order\Repositories\Interfaces\OrderRepositoryInterface;
use App\Order\DTO\ListOrdersDto;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Order\Models\Order;

class OrderRepository implements OrderRepositoryInterface
{
    public function list(ListOrdersDto $dto): LengthAwarePaginator
    {
        return Order::query()
            ->where('user_id', $dto->userId)
            ->with(['items', 'paymentMethod'])
            ->when(
                $dto->status !== null,
                fn($query) => $query->where('status', $dto->status)
            )
            ->orderBy($dto->sort, $dto->order)
            ->paginate(
                perPage: $dto->perPage,
                page: $dto->page,
            )
            ->withQueryString();
    }
}
