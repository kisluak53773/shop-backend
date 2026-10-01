<?php

declare(strict_types=1);

namespace App\User\DTO;

use App\Enums\UserRole;

readonly class RegisterUserDto
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public UserRole $role = UserRole::CUSTOMER,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            password: $data['password'],
            role: isset($data['role']) ? UserRole::from($data['role']) : UserRole::CUSTOMER
        );
    }
}
