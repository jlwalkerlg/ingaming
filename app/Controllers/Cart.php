<?php

namespace App\Controllers;

use Bellona\Http\Controller;
use App\Models\Game;
use Bellona\Http\Request;
use Bellona\Support\Facades\Cookie;
use App\Models\Cart as CartModel;
use App\Models\CartProduct;
use Bellona\Support\Facades\DB;
use Bellona\Support\Facades\CSRF;
use Bellona\Validation\Validator;
use Bellona\Support\Facades\Encrypt;
use Bellona\Support\Facades\Auth;

class Cart extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $cartId = $user->cart_id;
        $cart = $cartId ? CartModel::find($cartId) : null;

        if (!$cart) {
            $products = [];
        } else {
            $products = DB::table('cart_products as cp')->join('games', 'cp.game_id', '=', 'games.id')->select('cp.id', 'cp.quantity', 'games.id as game_id', 'games.case_img', 'games.title', 'games.price')->where('cart_id', $cart->id)->get();
        }

        $totalPrice = array_reduce($products, function ($carry, $product) {
            return $carry + ($product->price * $product->quantity);
        });

        $subtotal = $totalPrice;

        $csrfToken = CSRF::token();

        $data['title'] = 'Cart';
        $data['csrf_token'] = $csrfToken;
        $data['products'] = $products;
        $data['totalPrice'] = $totalPrice ?? 0.00;
        $data['subtotal'] = $subtotal ?? 0.00;
        $data['scripts'] = ['cart'];

        render('cart/cart', $data);
    }


    public function addProduct(Request $request)
    {
        $response = $this->addProductToCart($request);

        if (!$request->data('buy_now')) {
            echo json_encode($response);
            exit;
        }

        if (!$response['success']) {
            Session::flash('alert', $response['msg']);
            back();
        }

        redirect("/cart");
    }


    private function addProductToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|format:int'
        ]);

        $game = Game::find($request->data('product_id'));
        if (!$game) {
            $response['success'] = false;
            $response['msg'] = 'Failed to find game in database.';
            return $response;
        }

        if ($user = Auth::user()) {
            $cartId = $user->cart_id;
        } elseif ($cookie = Cookie::get(CART_COOKIE_NAME)) {
            $cartId = Encrypt::decryptString($cookie);
        }

        if (isset($cartId)) {
            $cart = CartModel::find($cartId);
        }

        if (!isset($cart)) {
            $cart = new CartModel;
            if (!$cart->save()) {
                $response['success'] = false;
                $response['msg'] = 'Failed to create new cart.';
                return $response;
            }
        }

        $cartProduct = CartProduct::where([
            ['cart_id', $cart->id],
            ['game_id', $game->id]
        ])->first();

        if ($cartProduct) {
            $response['success'] = true;
            $response['msg'] = 'Product already in cart.';
            $response['cartId'] = $cart->id;
            return $response;
        }

        $cartProduct = new CartProduct;
        $cartProduct->cart_id = $cart->id;
        $cartProduct->game_id = $game->id;
        $cartProduct->quantity = 1;

        if (!$cartProduct->save()) {
            $response['success'] = false;
            $response['msg'] = 'Failed to add product to cart.';
            return $response;
        }

        // Only set cookie if there is not a cookie with a different cartId from the logged in user.
        if (!$user || (isset($cookie) && isset($cartId) && $cartId === $user->cart_id)) {
            $cookie = Encrypt::encryptString($cart->id);
            Cookie::set(CART_COOKIE_NAME, $cookie, CART_COOKIE_DURATION, null, null, null, true);
        }

        [$cartCount, $cartTotal] = $this->getCartValues($cart->id);

        $response['success'] = true;
        $response['msg'] = 'Product added to cart.';
        $response['cartCount'] = $cartCount;
        $response['cartTotal'] = $cartTotal;

        return $response;
    }


    public function updateProduct(Request $request, CartProduct $product)
    {
        $validator = new Validator($request->data(), [
            'quantity' => 'required|min:1|format:int'
        ]);

        $validator->run();

        if (!$validator->validates()) {
            $response = [
                'success' => false,
                'msg' => 'Quantity must not be less than 1.'
            ];
            echo json_encode($response);
            exit;
        }

        $product->assign($request->data());

        if ($product->save()) {
            [$cartCount, $cartTotal] = $this->getCartValues($product->cart_id);

            $response = [
                'success' => true,
                'msg' => 'Product quantity updated.',
                'cartTotal' => $cartTotal
            ];
        } else {
            $response = [
                'success' => false,
                'msg' => 'Failed to update product quantity.'
            ];
        }

        echo json_encode($response);
    }


    public function deleteProduct(CartProduct $product)
    {
        if ($product->delete()) {
            [$cartCount, $cartTotal] = $this->getCartValues($product->cart_id);

            $response = [
                'success' => true,
                'msg' => 'Product deleted from cart.',
                'cartCount' => $cartCount,
                'cartTotal' => $cartTotal
            ];
        } else {
            $response = [
                'success' => false,
                'msg' => 'Failed to delete product from cart.'
            ];
        }

        echo json_encode($response);
    }

    private function getCartValues($cartId)
    {
        $sql = 'SELECT COUNT(*), SUM(price * quantity) FROM (
            SELECT cp.cart_id, games.price, cp.quantity
            FROM cart_products as cp
            INNER JOIN carts ON cp.cart_id = carts.id
            INNER JOIN games ON games.id = cp.game_id
            WHERE carts.id = ?
            ) src';
        $params = [$cartId];

        $sth = DB::query($sql, $params);
        $sth->execute();

        $result = $sth->fetch(\PDO::FETCH_NUM);

        return $result;
    }
}
