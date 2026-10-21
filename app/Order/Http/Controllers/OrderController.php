<?php

declare(strict_types=1);

namespace App\Order\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Order\Actions\CreateOrderAction;
use App\Order\DTO\CreateOrderDTO;
use App\Order\Http\Requests\CreateOrderRequest;
use App\Order\Http\Resources\OrderResource;
use App\Cart\Http\Middleware\ResolveCartMiddleware;
use App\Order\Services\Interfaces\OrderServiceInterface;
use App\Order\Http\Requests\ListOrdersRequest;
use App\Order\DTO\ListOrdersDTO;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OrderController extends Controller
{
    public function __construct(
        private CreateOrderAction $createOrderAction,
        private OrderServiceInterface $orderService,
    )
    {}

    public function index(ListOrdersRequest $request): AnonymousResourceCollection
    {
        $user = $request->user();
        $dto = ListOrdersDTO::fromArray($request->validated(), $user->id);
        $orders = $this->orderService->paginateUserOrders($dto);

        return OrderResource::collection($orders);
    }

    public function store(CreateOrderRequest $request): OrderResource
    {
        $user = auth('sanctum')->user();
        $cartToken = $request->header(ResolveCartMiddleware::CART_HEADER);
        $data = $request->validated();
        $dto = CreateOrderDTO::fromArray($data);
        $order = $this->createOrderAction->execute($user, $dto, $cartToken);
        
        return OrderResource::make($order);
    }
}