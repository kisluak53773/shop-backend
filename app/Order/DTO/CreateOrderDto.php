<?php

declare(strict_types=1);

namespace App\Order\DTO;

readonly class CreateOrderDto
{
    public function __construct(
        public int $paymentMethodId,
        public ?string $customerName,
        public ?string $customerEmail,
        public ?string $customerPhone,
        public ?string $customerAddress,
        public ?string $customerCity,
        public ?string $customerIndex,
        public ?string $note,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            paymentMethodId: (int) $data['paymentMethodId'],
            customerName: $data['customerName'] ?? null,
            customerEmail: $data['customerEmail'] ?? null,
            customerPhone: $data['customerPhone'] ?? null,
            customerAddress: $data['customerAddress'] ?? null,
            customerCity: $data['customerCity'] ?? null,
            customerIndex: $data['customerIndex'] ?? null,
            note: $data['note'] ?? null,
        );
    }
}
