<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Cart;

class CartPolicy
{
    public function crud(User $user, Cart $cart)
    {
        return $user->id === $cart->user_id;
    }
}
