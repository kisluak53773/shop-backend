<?php

declare(strict_types=1);

namespace App\Order\Services\Interfaces;

use Illuminate\Support\Collection;

interface PaymentMethodServiceInterface
{
    public function getActivePaymentMethods(): Collection;
}