<?php

declare(strict_types=1);

namespace App\Cart\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddCartItemReqeuest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ];
    }
}