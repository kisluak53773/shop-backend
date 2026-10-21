<?php

declare(strict_types=1);

namespace App\User\DTO;

readonly class ForgotPasswordDto
{
    public function __construct(
        public string $email,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            email: $data['email'],
        );
    }
}
