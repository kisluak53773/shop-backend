<?php

declare(strict_types=1);

namespace App\User\Services\Interfaces;

use App\User\DTO\RegisterUserDto;
use App\User\DTO\LoginUserDto;
use App\User\Models\User;

interface AuthServiceInterface
{
    public function registerUser(RegisterUserDto $dto): User;

    public function loginUser(LoginUserDto $dto): User;
}
