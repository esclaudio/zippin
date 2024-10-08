<?php

use App\Exceptions\InvalidOrderTotalException;
use App\Mail\OrderCreated;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\OrderStoreService;
use Illuminate\Support\Facades\Mail;

it('creates an order and sends email', function () {
    Mail::fake();

    $user = User::factory()->create();
    $products = Product::factory()->count(2)->create();

    $attributes = [
        'lines' => [
            [
                'product_id' => $products[0]->id,
                'quantity' => 2,
            ],
            [
                'product_id' => $products[1]->id,
                'quantity' => 1,
            ],
        ],
        'billing' => [
            'name' => 'Test Billing',
            'phone' => '1122223333',
            'email' => 'test@example.com',
            'address1' => 'Olleros 123',
            'address2' => '',
            'zipcode' => '1426',
            'city' => 'Colegiales',
            'province' => 'CABA',
            'country' => 'Argentina',
        ],
        'shipping' => [
            'name' => 'Test Shipping',
            'phone' => '1122223333',
            'email' => 'test@example.com',
            'address1' => 'Olleros 123',
            'address2' => '',
            'zipcode' => '1426',
            'city' => 'Colegiales',
            'province' => 'CABA',
            'country' => 'Argentina',
        ],
        'currency_code' => 'ARS',
        'subtotal' => $products->sum('price'),
        'discount' => 10,
    ];

    $orderStoreService = new OrderStoreService;
    $order = $orderStoreService->handle($attributes, $user);

    expect($order->lines)->toHaveCount(2);

    Mail::assertQueued(OrderCreated::class);
});

it('throws an InvalidOrderTotalException if total does not match calculated total', function () {
    $user = User::factory()->create();
    $products = Product::factory()->count(2)->create();
    $total = $products->sum('price') + 1;
    $attributes = [
        'lines' => [
            [
                'product_id' => $products[0]->id,
                'quantity' => 2,
            ],
            [
                'product_id' => $products[1]->id,
                'quantity' => 1,
            ],
        ],
        'billing' => [],
        'shipping' => [],
        'currency_code' => 'ARS',
        'subtotal' => $total,
        'discount' => 0,
        'total' => $total,
    ];

    $orderStoreService = new OrderStoreService;

    $this->expectException(InvalidOrderTotalException::class);

    $orderStoreService->handle($attributes, $user);
});

it('returns a specific order', function () {
    $order = Order::factory()->create();

    $this->actingAsJwtUser($order->user)
        ->getJson("/api/v1/orders/{$order->hashId}")
        ->assertOk();
});

it('does not show order of another user', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $order = Order::factory()->for($user2)->create();

    $this->actingAsJwtUser($user1)
        ->getJson("/api/v1/orders/{$order->hashId}")
        ->assertForbidden();
});

it('returns paginated orders for the user', function () {
    $user = User::factory()->create();

    Order::factory()->count(3)->for($user)->create();

    $this->actingAsJwtUser($user)
        ->getJson('/api/v1/orders')
        ->assertOk()
        ->assertJsonCount(3, 'data')
        ->assertJsonStructure(['data', 'links', 'meta']);
});

it('does not show orders of another user', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    Order::factory()->count(3)->for($user2)->create();

    $this->actingAsJwtUser($user1)
        ->getJson('/api/v1/orders')
        ->assertOk()
        ->assertJsonCount(0, 'data')
        ->assertJsonStructure(['data', 'links', 'meta']);
});

it('returns all orders for the admin', function () {
    $user = User::factory()->create();
    $admin = User::factory()->admin()->create();

    Order::factory()->count(5)->for($user)->create();

    $this->actingAsJwtUser($admin)
        ->getJson('/api/v1/orders')
        ->assertOk()
        ->assertJsonCount(5, 'data')
        ->assertJsonStructure(['data', 'links', 'meta']);
});
