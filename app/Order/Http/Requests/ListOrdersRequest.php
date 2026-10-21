<?php

declare(strict_types=1);

namespace App\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\OrderStatus;

class ListOrdersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'page'     => ['sometimes', 'integer', 'min:1'],
            'perPage' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'status'   => ['sometimes', 'string', Rule::enum(OrderStatus::class)],
            'sort'     => ['sometimes', 'string', 'in:created_at,total_amount,number'],
            'order'    => ['sometimes', 'string', 'in:asc,desc'],
        ];
    }
}
