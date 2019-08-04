<?php

namespace App\Providers;

use Bellona\Support\ServiceProvider;
use Bellona\Support\Facades\View;
use Bellona\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $cart = Auth::user()->cart();
        $products = $cart->products();
        $cartCount = count($products);
        $cartTotal = array_reduce($products, function ($carry, $product) {
            return $carry + $product->price * $product->quantity;
        }, 0);
        View::share('cartCount', $cartCount);
        View::share('cartTotal', $cartTotal);
    }
}
