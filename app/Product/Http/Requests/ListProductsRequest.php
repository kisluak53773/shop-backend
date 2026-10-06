<?php

declare(strict_types=1);

namespace App\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class ListProductsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'page' => 'sometimes|integer|min:1',
            'perPage' => 'sometimes|integer|min:1',
            'search' => 'sometimes|string|max:100',
            'sort' => 'sometimes|string|in:name,price,created_at',
            'order' => 'sometimes|string|in:asc,desc',
        ];
    }
}
