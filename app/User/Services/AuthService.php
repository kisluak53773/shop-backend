<?php

declare(strict_types=1);

namespace App\User\Services;

use App\User\DTO\ForgotPasswordDto;
use App\User\DTO\RegisterUserDto;
use App\User\DTO\LoginUserDto;
use App\User\DTO\ResetPasswordDto;
use App\User\Mail\ResetPasswordEmail;
use App\User\Services\Interfaces\AuthServiceInterface;
use App\User\Models\User;
use App\User\Exceptions\InvalidCredentialsException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\User\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Validation\ValidationException;
use App\User\Exceptions\ResetPasswordException;

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

    public function forgotPassword(ForgotPasswordDto $dto): void
    {
        $user = User::where('email', $dto->email)->firstOrFail();
        $token = Password::createToken($user);

        Mail::to($user->email)->send(new ResetPasswordEmail($user, $token));
    }

    public function resetPassword(ResetPasswordDto $dto): void
    {
        $status = Password::reset(
            [
                'token' => $dto->token,
                'email' => $dto->email,
                'password' => $dto->password,
            ],
            function (User $user, string $password): void {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();

                $user->tokens()->delete();

                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw new ResetPasswordException($status);
        }
    }
}
