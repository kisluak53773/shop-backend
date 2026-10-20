<?php

declare(strict_types=1);

namespace App\User\Services\Interfaces;

use App\User\DTO\RegisterUserDto;
use App\User\DTO\LoginUserDto;
use App\User\DTO\ForgotPasswordDto;
use App\User\DTO\ResetPasswordDto;
use App\User\Models\User;

interface AuthServiceInterface
{
    public function registerUser(RegisterUserDto $dto): User;

    public function loginUser(LoginUserDto $dto): User;

    public function forgotPassword(ForgotPasswordDto $dto): void;

    public function resetPassword(ResetPasswordDto $dto): void;
}
