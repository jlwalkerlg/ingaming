<?php

namespace App\Policies;

use App\Models\User;
use Bellona\Support\Facades\Cookie;
use App\Models\Cart;
use Bellona\Support\Facades\Auth;
use Bellona\Support\Facades\Encrypt;

class CartPolicy
{
    public function crud(?User $user, Cart $cart)
    {
        if ($user = Auth::user()) {
            $cartId = $user->cart_id;
        } elseif ($cookie = Cookie::get(CART_COOKIE_NAME)) {
            $cartId = Encrypt::decryptString($cookie);
        }

        return isset($cartId) && $cart->id === $cartId;
    }
}
