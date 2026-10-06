<?php

declare(strict_types=1);

namespace App\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Product\DTO\ListProductsDto;
use App\Product\Http\Requests\ListProductsRequest;
use App\Product\Services\Interfaces\ProductServiceInterface;
use App\Product\Http\Resources\ProductResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    public function __construct(
        private ProductServiceInterface $productService,
    ) {}

    public function index(ListProductsRequest $request): AnonymousResourceCollection
    {
        $data = $request->validated();
        $paginationDto = ListProductsDto::fromArray($data);
        $products = $this->productService->paginated($paginationDto);

        return ProductResource::collection($products);
    }
}
