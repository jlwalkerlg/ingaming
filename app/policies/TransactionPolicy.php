<?php

namespace App\Policies;

use App\Models\User;
use Bellona\Support\Facades\Cookie;
use Bellona\Support\Facades\Auth;
use App\Models\Transaction;
use Bellona\Support\Facades\Encrypt;

class TransactionPolicy
{
    public function view(?User $user, Transaction $transaction)
    {
        if ($user = Auth::user()) {
            $cartId = $user->cart_id;
        } elseif ($cookie = Cookie::get(CART_COOKIE_NAME)) {
            $cartId = Encrypt::decryptString($cookie);
        }

        return isset($cartId) && $transaction->cart_id === $cartId;
    }
}
