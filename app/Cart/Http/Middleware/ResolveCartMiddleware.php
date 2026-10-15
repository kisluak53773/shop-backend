<?php

declare(strict_types=1);

namespace App\Cart\Http\Middleware;

use App\Cart\Services\Interfaces\CartServiceInterface;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveCartMiddleware
{
    public const CART_HEADER = 'X-Cart-Token';

    public function __construct(private CartServiceInterface $cartService)
    {}

    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header(self::CART_HEADER);
        $user = $request->user();
        $cart = $this->cartService->resolve($user, $token);
        $request->attributes->set('cart', $cart);

        return $next($request);
    }
}