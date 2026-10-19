<?php

declare(strict_types=1);

namespace App\User\Services;

use App\User\DTO\RegisterUserDto;
use App\User\DTO\LoginUserDto;
use App\User\Services\Interfaces\AuthServiceInterface;
use App\User\Models\User;
use App\User\Exceptions\InvalidCredentialsException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\User\Mail\WelcomeEmail;

class AuthService implements AuthServiceInterface
{
    public function registerUser(RegisterUserDto $dto): User
    {
        $user = User::create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => $dto->password,
            'role' => $dto->role,
            'phone' => $dto->phone,
            'address' => $dto->address,
            'city' => $dto->city,
            'index' => $dto->index,
        ]);

        Mail::to($user->email)->send(new WelcomeEmail($user));

        return $user;
    }

    public function loginUser(LoginUserDto $dto): User
    {
        $user = User::where('email', $dto->email)->first();

        if (!$user || !Hash::check($dto->password, $user->password)) {
            throw new InvalidCredentialsException();
        }

        return $user;
    }
}
