<?php

declare(strict_types=1);

namespace App\User\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User\DTO\LoginUserDto;
use App\User\Http\Requests\RegisterUserRrequest;
use Illuminate\Http\JsonResponse;
use App\User\DTO\RegisterUserDto;
use App\User\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use App\Cart\Services\Interfaces\CartServiceInterface;
use App\Cart\Http\Middleware\ResolveCartMiddleware;
use App\Cart\Http\Resources\CartResource;
use App\User\Actions\LoginUserAction;
use App\User\Actions\RegisterUserAction;
use App\User\Http\Resources\UserResource;

class AuthController extends Controller
{
    public function __construct(
        private CartServiceInterface $cartService,
        private LoginUserAction $loginUserAction,
        private RegisterUserAction $registerUserAction,
    ) {}

    public function register(RegisterUserRrequest $request): JsonResponse
    {
        $data = $request->validated();
        $userDto = RegisterUserDto::fromArray($data);
        $cartToken = $request->header(ResolveCartMiddleware::CART_HEADER);
        $result = $this->registerUserAction->execute($userDto, $cartToken);

        return response()->json([
            'token' => $result->token,
            'user' => UserResource::make($result->user),
            'cart' => CartResource::make($result->cart->load('items.product.primaryImage')),
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();
        $userDto = LoginUserDto::fromArray($data);
        $cartToken = $request->header(ResolveCartMiddleware::CART_HEADER);
        $result = $this->loginUserAction->execute($userDto, $cartToken);

        return response()->json([
            'token' => $result->token,
            'user' => UserResource::make($result->user),
            'cart' => CartResource::make($result->cart->load('items.product.primaryImage')),
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
