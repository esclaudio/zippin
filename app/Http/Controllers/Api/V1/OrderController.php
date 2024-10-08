<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\OrderRequest;
use App\Http\Resources\Api\OrderResource;
use App\Models\Order;
use App\Services\OrderStoreService;
use Carbon\CarbonInterval;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class OrderController
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $user = $request->user('api');
        $orders = $user->is_admin ? Order::query() : $user->orders();

        return OrderResource::collection($orders->latest()->paginate());
    }

    public function store(OrderRequest $request, OrderStoreService $orderStoreService): OrderResource
    {
        $order = $orderStoreService->handle($request->validated(), $request->user('api'));

        return new OrderResource($order);
    }

    public function show(Order $order): OrderResource
    {
        $this->authorize('view', $order);

        return cache()->remember(
            "order_{$order->hashId}",
            CarbonInterval::day(),
            fn () => new OrderResource($order->load(['lines']))
        );
    }
}
