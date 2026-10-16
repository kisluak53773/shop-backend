<?php

declare(strict_types=1);

namespace App\Category\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use App\Product\Http\Resources\ImageResource;
use App\Product\Http\Resources\ProductResource;

/** @mixin \App\Category\Models\Category */
class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'parentId' => $this->parent_id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'sortOrder' => $this->sort_order,
            'productsCount' => $this->whenCounted('products'),
            'children' => $this->whenLoaded(
                'childrenRecursive',
                fn() => CategoryResource::collection($this->childrenRecursive)
            ),
            'products' => $this->whenLoaded(
                'products',
                fn() => ProductResource::collection($this->products)
            ),
            'image' => $this->whenLoaded(
                'image',
                fn() => $this->image ? ImageResource::make($this->image) : null
            ),
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
