<?php

namespace App\Controllers;

use Bellona\Http\Controller;

use Bellona\Support\Facades\Auth;
use Bellona\Support\Facades\Session;
use App\Lib\Payment\Payment;
use Bellona\Http\Request;
use App\Models\Transaction;
use App\Lib\Mail\MailFactory;

class Checkout extends Controller
{
    public function index(Payment $pg)
    {
        $cart = $this->getCart();

        $products = $cart->products();

        if (!$products) {
            Session::flash('alert', 'There are no items in your cart!');
            redirect('/cart');
        }

        $pg->renderCheckout($products);
    }


    public function confirm(Request $request, Payment $pg)
    {
        $request->validate([
            'name' => 'required|min:5',
            'email' => 'required|format:email',
            'address.line1' => 'required',
            'address.city' => 'required',
            'address.country' => 'required',
            'address.postal_code' => 'required|format:postcode',
            'payment_method_nonce' => 'required'
        ]);

        $cart = $this->getCart();

        $subtotal = $cart->subtotal();

        if ($subtotal !== 0 && !$subtotal) {
            Session::flash('alert', 'Error retrieving items from database.');
            back();
        }

        $pg->setData($request->data());
        $pg->setCartId($cart->id);

        $response = $pg->confirmPayment($subtotal);

        if (isset($response['success'])) {
            $transaction = $pg->getTransaction();
            $this->onSuccess($transaction);
            redirect("/checkout/success/{$transaction->id}");
        } else {
            Session::flash('error', $response['error']);
            redirect('/checkout');
        }
    }


    private function getCart()
    {
        return Auth::user()->cart();
    }


    private function onSuccess($transaction)
    {
        // Create new cart for user.
        Auth::user()->createCart();

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
