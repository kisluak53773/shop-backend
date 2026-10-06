<?php

declare(strict_types=1);

namespace App\Product\Services\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Product\DTO\ListProductsDto;

interface ProductServiceInterface
{
    public function paginated(ListProductsDto $dto): LengthAwarePaginator;
}
