<?php

namespace App\Controllers;

use Bellona\Http\Controller;
use App\Models\Game;
use Bellona\Http\Request;
use App\Models\CartProduct;
use Bellona\Support\Facades\DB;
use Bellona\Support\Facades\CSRF;
use Bellona\Validation\Validator;
use Bellona\Support\Facades\Auth;

class Cart extends Controller
{
    public function show()
    {
        $cart = Auth::user()->cart();

        $products = DB::table('cart_products as cp')->join('games', 'cp.game_id', '=', 'games.id')->select('cp.id', 'cp.quantity', 'games.id as game_id', 'games.case_img', 'games.title', 'games.price')->where('cp.cart_id', $cart->id)->get();

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

        $cart = Auth::user()->cart();

        $cartProduct = CartProduct::where([
            ['cart_id', $cart->id],
            ['game_id', $game->id]
        ])->first();

        if ($cartProduct) {
            $response['success'] = true;
            $response['msg'] = 'Product already in cart.';
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
                'cartCount' => $cartCount,
                'cartTotal' => $cartTotal,
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
