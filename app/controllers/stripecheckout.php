<?php

namespace App\Controllers;

use Bellona\Http\Controller;

use Bellona\Support\Facades\Auth;
use Bellona\Support\Facades\Cookie;
use App\Models\Cart;
use Bellona\Support\Facades\DB;
use Bellona\Support\Facades\Session;
use App\Lib\Payment\Payment;
use Bellona\Validation\Validator;
use Bellona\Http\Request;
use App\Models\Transaction;
use App\Lib\Mail\MailFactory;
use Bellona\Support\Facades\Encrypt;

class Checkout extends Controller
{
    public function index(Payment $pg)
    {
        $cart = $this->getCart();

        if (!$cart) {
            Session::flash('alert', 'Failed to retrieve cart from database.');
            back();
        }

        $products = $this->getCartProducts($cart->id);

        if (!$products) {
            Session::flash('alert', 'There are no items in your cart!');
            redirect("/cart/{$cart->id}");
        }

        $pg->renderCheckout($products);
    }


    private function getCart()
    {
        if ($user = Auth::user()) {
            $cartId = $user->cart_id;
        } elseif ($cookie = Cookie::get(CART_COOKIE_NAME)) {
            $cartId = Encrypt::decryptString($cookie);
        }

        if (isset($cartId)) {
            $cart = Cart::find($cartId);
        }

        return $cart ?? null;
    }


    private function getCartProducts($cartId)
    {
        $products = DB::table('cart_products as cp')->join('games', 'cp.game_id', '=', 'games.id')->join('platforms', 'games.platform_id', '=', 'platforms.id')->where('cart_id', $cartId)->select('games.title', 'games.price', 'cp.quantity', 'games.case_img', 'games.cover_img', 'platforms.short_name as platform')->get();

        return $products;
    }


    public function confirm(Request $request, Payment $pg)
    {
        $validator = new Validator($request->data(), [
            'name' => 'required|min:5',
            'email' => 'required|format:email',
            'address.line1' => 'required',
            'address.city' => 'required',
            'address.country' => 'required|size:2',
            'address.postal_code' => 'required|format:postcode',
            'type' => 'required',
            'last4' => 'format:int|size:4'
        ]);
        $validator->run();

        if (!$validator->validates()) {
            $response['error'] = true;
            $response['validationErrors'] = $validator->getErrors();
            echo json_encode($response);
            exit;
        }

        $cart = $this->getCart();

        if (!$cart) {
            Session::flash('alert', 'Failed to retrieve cart from database.');
            $response['error'] = true;
            $response['fatal'] = true;
            $response['location'] = url('/');
            echo json_encode($response);
            exit;
        }

        $subtotal = $this->getCartSubtotal($cart->id);

        if ($subtotal !== 0 && !$subtotal) {
            Session::flash('alert', 'Error retrieving items from database.');
            $response['error'] = true;
            $response['fatal'] = true;
            $response['location'] = url("/cart/{$cart->id}");
            echo json_encode($response);
            exit;
        }

        $pg->setData($request->data());
        $pg->setCartId($cart->id);
        $response = $pg->confirmPayment($subtotal);

        if (isset($response['success'])) {
            $transaction = $pg->getTransaction();
            $this->onSuccess($transaction);
            $response['location'] = url("/checkout/success/{$transaction->id}");
        } elseif (isset($response['error']) && $response['fatal']) {
            Session::flash('alert', $response['error']);
            $response['location'] = url("/cart/{$cart->id}");
        }

        echo json_encode($response);
    }


    private function getCartSubtotal($cartId)
    {
        $sql = 'SELECT SUM(games.price * cp.quantity)
        FROM cart_products as cp
        INNER JOIN games ON cp.game_id = games.id
        WHERE cp.cart_id = ?
        ';
        $params = [$cartId];

        $sth = DB::query($sql, $params);
        $sth->execute();

        return $sth->fetchColumn();
    }


    private function onSuccess($transaction)
    {
        // Delete items from cart.
        DB::table('cart_products as cp')->where('cp.cart_id', $transaction->cart_id)->delete();

        // Email verification.
        $gmail = app(MailFactory::class)->make('gmail');
        $to = $transaction->email;
        $subject = 'Order confirmation.';
        $msg = "Your order of £{$transaction->amount} was successful and will be shipped to postcode {$transaction->postcode}.";
        $gmail->compose($to, $subject, $msg);
        $gmail->send();
    }


    public function success(Transaction $transaction)
    {
        $data['title'] = 'Success';
        $data['transaction'] = $transaction;
        $data['scripts'] = ['checkout.success'];

        render('checkout/success', $data);
    }
}
