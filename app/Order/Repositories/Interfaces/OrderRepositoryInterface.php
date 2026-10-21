<?php

declare(strict_types=1);

namespace App\Order\Repositories\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Order\DTO\ListOrdersDto;

interface OrderRepositoryInterface
{
    public function list(ListOrdersDto $dto): LengthAwarePaginator;
}
