<?php

declare(strict_types=1);

namespace App\Product\Repositories\Interfaces;

use App\Product\DTO\TrendingProductsDto;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Product\DTO\ListProductsDto;

interface ProductRepositoryInterface
{
    public function paginated(ListProductsDto $dto): LengthAwarePaginator;

    public function trending(TrendingProductsDto $dto): LengthAwarePaginator;
}
