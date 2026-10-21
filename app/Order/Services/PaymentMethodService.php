<?php

declare(strict_types=1);

namespace App\Order\Services;

use App\Order\Services\Interfaces\PaymentMethodServiceInterface;
use Illuminate\Support\Collection;
use App\Order\Models\PaymentMethod;

class PaymentMethodService implements PaymentMethodServiceInterface
{
    public function getActivePaymentMethods(): Collection
    {
        return PaymentMethod::active()->get();
    }
}
