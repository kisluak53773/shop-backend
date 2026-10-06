<?php

declare(strict_types=1);

namespace App\Product\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/** @mixin \App\Product\Models\Image */
class ImageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'path' => Storage::url($this->path),
            'mobilePath' => $this->mobile_path ? Storage::url($this->mobile_path) : null,
            'isPrimary' => $this->is_primary,
            'sortOrder' => $this->sort_order,
            'altText' => $this->alt_text,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
