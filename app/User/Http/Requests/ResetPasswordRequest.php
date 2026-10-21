<?php

declare(strict_types=1);

namespace App\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'token' => 'required|string',
            'password' => 'required|string|min:8',
            'email' => 'email|required|exists:users,email',
        ];
    }
}
