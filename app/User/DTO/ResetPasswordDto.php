<?php

declare(strict_types=1);

namespace App\User\DTO;

readonly class ResetPasswordDto
{
    public function __construct(
        public string $token,
        public string $password,
        public string $email,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            token: $data['token'],
            password: $data['password'],
            email: $data['email'],
        );
    }
}
