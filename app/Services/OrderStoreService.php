<?php

namespace App\Services;

use App\Enums\AddressType;
use App\Exceptions\InvalidOrderTotalException;
use App\Jobs\GenerateInvoice;
use App\Mail\OrderCreated;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderStoreService
{
    public function handle(array $attributes, User $user): Order
    {
        $orderLines = $this->getOrderLines($attributes);
        $billingAddress = $this->getBillingAddress($attributes);
        $shippingAddress = $this->getShippingAddress($attributes);

        $subtotal = array_sum(array_column($orderLines, 'total'));
        $total = $subtotal - (float) $attributes['discount'];

        if (isset($attributes['total'])) {
            $diff = abs($total - (float) $attributes['total']);

            if ($diff > 0.01) {
                throw new InvalidOrderTotalException($total, $attributes['total']);
            }
        } else {
            $attributes['total'] = $total;
        }

        $order = new Order($attributes);
        $order->user()->associate($user);

        DB::beginTransaction();

        try {
            $order->save();
            $order->lines()->createMany($orderLines);
            $order->billingAddress()->create($billingAddress);
            $order->shippingAddress()->create($shippingAddress);

            $user->addresses()->firstOrCreate($billingAddress);
            $user->addresses()->firstOrCreate($shippingAddress);

            foreach ($orderLines as $orderLine) {
                DB::statement(<<<'SQL'
                    update `products` set `stock` = `stock` - ? where `id` = ?
                SQL, [
                    $orderLine['quantity'],
                    $orderLine['product_id'],
                ]);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        Mail::to($user)->queue(new OrderCreated($order));

        dispatch(new GenerateInvoice($order));

        return $order;
    }

    protected function getOrderLines(array $attributes): array
    {
        $lines = $attributes['lines'];
        $products = Product::query()
            ->whereIn('id', array_column($lines, 'product_id'))
            ->get()
            ->keyBy('id');

        return array_map(function (array $line) use ($products) {
            $product = $products[$line['product_id']];
            $quantity = (int) $line['quantity'];

            return [
                'product_id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'quantity' => $quantity,
                'price' => $product->price,
                'total' => $product->price * $quantity,
            ];
        }, $lines);
    }

    protected function getBillingAddress(array $attributes): array
    {
        return [
            ...$attributes['billing'],
            'type' => AddressType::Billing,
        ];
    }

    protected function getShippingAddress(array $attributes): array
    {
        return [
            ...$attributes['shipping'],
            'type' => AddressType::Shipping,
        ];
    }
}
