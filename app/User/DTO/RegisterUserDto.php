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
        public ?string $phone = null,
        public ?string $address = null,
        public ?string $city = null,
        public ?string $index = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            password: $data['password'],
            role: isset($data['role']) ? UserRole::from($data['role']) : UserRole::CUSTOMER,
            phone: $data['phone'] ?? null,
            address: $data['address'] ?? null,
            city: $data['city'] ?? null,
            index: $data['index'] ?? null,
        );
    }
}
