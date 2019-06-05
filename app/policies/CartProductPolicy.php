<?php

namespace App\Policies;

use App\Models\User;
use Bellona\Support\Facades\Cookie;
use App\Models\CartProduct;
use Bellona\Support\Facades\Auth;
use Bellona\Support\Facades\Encrypt;

class CartProductPolicy
{
    public function crud(?User $user, CartProduct $product)
    {
        if ($user = Auth::user()) {
            $cartId = $user->cart_id;
        } elseif ($cookie = Cookie::get(CART_COOKIE_NAME)) {
            $cartId = Encrypt::decryptString($cookie);
        }

        return isset($cartId) && $product->cart_id === $cartId;
    }
}
