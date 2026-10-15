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
use App\Cart\Services\Interfaces\CartServiceInterface;
use App\Cart\Http\Middleware\ResolveCartMiddleware;
use App\Cart\Http\Resources\CartResource;

class AuthController extends Controller
{
    public function __construct(
        private AuhtServiceInterface $authSevice,
        private CartServiceInterface $cartService
    ) {}

    public function register(RegisterUserRrequest $request): JsonResponse
    {
        $data = $request->validated();
        $userDto = RegisterUserDto::fromArray($data);
        $user = $this->authSevice->registerUser($userDto);
        $cart = $this->cartService->attachToUser($user, $request->header(ResolveCartMiddleware::CART_HEADER));
        $token = $user->createToken('access_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
            'cart' => CartResource::make($cart->load('items.product.primaryImage')),
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();
        $userDto = LoginUserDto::fromArray($data);
        $user = $this->authSevice->loginUser($userDto);
        $cart = $this->cartService->attachToUser($user, $request->header(ResolveCartMiddleware::CART_HEADER));
        $token = $user->createToken('access_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
            'cart' => CartResource::make($cart->load('items.product.primaryImage')),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        $cart = $this->cartService->createForGuest();

        return response()->json(['message' => 'Logged out successfully', 'cart' => CartResource::make($cart)]);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }
}
