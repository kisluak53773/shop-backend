<?php

declare(strict_types=1);

namespace App\User\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class ResetPasswordException extends Exception
{
    public function __construct(
        public readonly string $status,
    ) {
        parent::__construct(
            message: "Failed to reset password: {$status}"
        );
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'message' => $this->message,
        ], 400);
    }
}
