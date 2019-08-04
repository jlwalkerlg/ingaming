<?php

namespace App\Policies;

use App\Models\User;
use App\Models\CartProduct;
use App\Models\Cart;

class CartProductPolicy
{
    public function crud(User $user, CartProduct $product)
    {
        $cart = Cart::find($product->cart_id);
        return $user->id === $cart->user_id;
    }
}
