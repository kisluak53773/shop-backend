<?php

declare(strict_types=1);

namespace App\Category\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Category\Http\Requests\ListCategoriesRequest;
use App\Category\Http\Requests\CategoryTreeRequest;
use App\Category\Services\Interfaces\CategoryServiceInterface;
use App\Category\DTO\ListCategoriesDto;
use App\Category\DTO\CategoryTreeDto;
use App\Category\Http\Resources\CategoryResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends Controller
{
    public function __construct(private CategoryServiceInterface $categoryService) {}

    public function index(ListCategoriesRequest $request): AnonymousResourceCollection
    {
        $data = $request->validated();
        $dto = ListCategoriesDto::fromArray($data);
        $categories = $this->categoryService->list($dto);

        return CategoryResource::collection($categories);
    }

    public function tree(CategoryTreeRequest $request): AnonymousResourceCollection
    {
        $data = $request->validated();
        $dto = CategoryTreeDto::fromArray($data);
        $categories = $this->categoryService->tree($dto);

        return CategoryResource::collection($categories);
    }
}
