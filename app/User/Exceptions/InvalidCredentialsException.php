<?php

declare(strict_types=1);

namespace App\User\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class InvalidCredentialsException extends Exception
{
    protected $message = "Email or password is incorrect";

    public function render(): JsonResponse
    {
        return response()->json([
            'message' => $this->message,
        ], 401);
    }
}
