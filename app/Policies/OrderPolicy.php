<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    public function view(User $user, Order $order): bool
    {
        return $order->user()->is($user);
    }

    public function before(User $user, string $ability): ?bool
    {
        return $user->is_admin ? true : null;
    }
}
