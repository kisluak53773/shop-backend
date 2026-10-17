<?php

declare(strict_types=1);

namespace App\Order\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class NoCartException extends Exception
{
    protected $message = "A cart token is required for guest checkout.";

    public function render(): JsonResponse
    {
        return response()->json([
            'message' => $this->message,
        ], 422);
    }
}