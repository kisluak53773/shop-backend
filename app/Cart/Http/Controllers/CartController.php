<?php

declare(strict_types=1);

namespace App\Cart\Http\Controllers;

use App\Cart\Http\Resources\CartResource;
use App\Http\Controllers\Controller;
use App\Cart\Services\Interfaces\CartServiceInterface;
use App\Cart\DTO\AddCartItemDto;
use App\Cart\DTO\RemoveCartItemDto;
use App\Cart\DTO\UpdateCartItemDto;
use App\Cart\Http\Requests\AddCartItemReqeuest;
use App\Cart\Http\Requests\UpdateCartItemRequest;
use App\Cart\Models\Cart;
use Illuminate\Http\Request;
use App\Cart\Http\Middleware\ResolveCartMiddleware;

class CartController extends Controller
{
    public function __construct(
        private CartServiceInterface $cartService
    )
    {}

    public function index(Request $request): CartResource
    {
        $cart = $this->cart($request)->load('items.product.primaryImage');

        return CartResource::make($cart);
    }

    public function addItem(AddCartItemReqeuest $request): CartResource
    {
        $data = $request->validated();
        $dto = AddCartItemDto::fromArray($data);
        $currentCart = $this->cart($request);
        $updatedCart = $this->cartService->addItem($currentCart, $dto);

        return CartResource::make($updatedCart);
    }

    public function updateItem(UpdateCartItemRequest $request, int $itemId): CartResource
    {
        $data = $request->validated();
        $dto = UpdateCartItemDto::fromArray($data + ['itemId' => $itemId]);
        $currentCart = $this->cart($request);
        $updatedCart = $this->cartService->updateItem($currentCart, $dto);

        return CartResource::make($updatedCart);
    }

    public function removeItem(Request $request, int $itemId): CartResource
    {
        $dto = RemoveCartItemDto::fromArray(['itemId' => $itemId]);
        $currentCart = $this->cart($request);
        $updatedCart = $this->cartService->removeItem($currentCart, $dto);

        return CartResource::make($updatedCart);
    }

    public function clear(Request $request): CartResource
    {
        $currentCart = $this->cart($request);
        $updatedCart = $this->cartService->clear($currentCart);

        return CartResource::make($updatedCart);
    }

    private function cart(Request $request): Cart
    {
        return $request->attributes->get('cart')
            ?? $this->cartService->resolve($request->user(), $request->header(ResolveCartMiddleware::CART_HEADER));
    }
}