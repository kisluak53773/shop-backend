<?php

declare(strict_types=1);

namespace App\User\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User\DTO\LoginUserDto;
use App\User\Http\Requests\RegisterUserRrequest;
use Illuminate\Http\JsonResponse;
use App\User\Services\Interfaces\AuhtServiceInterface;
use App\User\DTO\RegisterUserDto;
use App\User\Http\Requests\LoginRequest;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(private AuhtServiceInterface $authSevice) {}

    public function register(RegisterUserRrequest $request): JsonResponse
    {
        $data = $request->validated();
        $userDto = RegisterUserDto::fromArray($data);
        $user = $this->authSevice->registerUser($userDto);
        $token = $user->createToken('access_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();
        $userDto = LoginUserDto::fromArray($data);
        $user = $this->authSevice->loginUser($userDto);
        $token = $user->createToken('access_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }
}
