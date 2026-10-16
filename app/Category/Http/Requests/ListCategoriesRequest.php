<?php

declare(strict_types=1);

namespace App\Category\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class ListCategoriesRequest extends FormRequest
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
            'search' => 'sometimes|string|max:255',
            'parentId' => 'sometimes|integer|exists:categories,id',
            'onlyRoots' => 'sometimes|boolean',
        ];
    }
}
