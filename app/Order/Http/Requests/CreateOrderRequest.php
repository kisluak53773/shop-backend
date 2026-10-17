<?php

declare(strict_types=1);

namespace App\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'paymentMethodId' => 'required|exists:payment_methods,id',
            'customerName' => 'required|string|max:255',
            'customerEmail' => 'required|email|max:255',
            'customerPhone' => 'nullable|string|max:50',
            'customerAddress' => 'nullable|string|max:255',
            'customerCity' => 'nullable|string|max:100',
            'customerIndex' => 'nullable|string|max:20',
            'note' => 'nullable|string|max:1000',
        ];
    }
}