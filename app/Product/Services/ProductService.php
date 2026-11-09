<?php

declare(strict_types=1);

namespace App\Product\Services;

use App\Product\DTO\TrendingProductsDto;
use App\Product\Services\Interfaces\ProductServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Product\Repositories\Interfaces\ProductRepositoryInterface;
use App\Product\DTO\ListProductsDto;

class ProductService implements ProductServiceInterface
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
    ) {}

    public function paginated(ListProductsDto $dto): LengthAwarePaginator
    {
        return $this->productRepository->paginated($dto);
    }

    public function trending(TrendingProductsDto $dto): LengthAwarePaginator
    {
        $products = $this->productRepository->trending($dto);

        return $products;
    }
}
