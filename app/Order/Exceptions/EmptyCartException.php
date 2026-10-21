<?php

declare(strict_types=1);

namespace App\Order\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class EmptyCartException extends Exception
{
    protected $message = "Cart is empty";

    public function render(): JsonResponse
    {
        return response()->json([
            'message' => $this->message,
        ], 422);
    }
}
