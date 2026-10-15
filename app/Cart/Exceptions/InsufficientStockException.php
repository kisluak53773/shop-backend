<?php

namespace App\Cart\Exceptions;

use App\Product\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class InsufficientStockException extends Exception
{
    public function __construct(
        public readonly Product $product, 
        public readonly int $requested, 
        public readonly int $available
    ) {
        parent::__construct(
            message: "Insufficient stock for product {$product->name}. Requested: {$requested}, Available: {$available}"
        );
    }

    public function context(): array
    {
        return [
            'product_id' => $this->product->id,
            'product'    => $this->product->name,
            'requested'  => $this->requested,
            'available'  => $this->available,
        ];
    }

    public function render(Request $request): ?JsonResponse
    {
        if (!$request->expectsJson()) {
            return null;
        }

        return response()->json([
            'message' => $this->getMessage(),
            'errors' => [
                'quantity' => $this->getMessage(),
            ],
            'context' => $this->context(),
        ], 400);
    }
}