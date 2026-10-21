<?php

declare(strict_types=1);

namespace App\Order\Http\Controllers;

use App\Order\Services\Interfaces\PaymentMethodServiceInterface;
use App\Order\Http\Resources\PaymentMethodResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Controllers\Controller;

class PaymentMethodController extends Controller
{
    public function __construct(
        private PaymentMethodServiceInterface $paymentMethodService
    ) {}

    public function index(): AnonymousResourceCollection
    {
        $paymentMethods = $this->paymentMethodService->getActivePaymentMethods();

        return PaymentMethodResource::collection($paymentMethods);
    }
}
