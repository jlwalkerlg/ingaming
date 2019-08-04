<?php

namespace App\Controllers;

use Bellona\Http\Controller;

use Bellona\Support\Facades\Auth;
use App\Models\Cart;
use Bellona\Support\Facades\Session;
use App\Lib\Payment\Payment;
use Bellona\Validation\Validator;
use Bellona\Http\Request;
use App\Models\Transaction;
use App\Lib\Mail\MailFactory;

class StripeCheckout extends Controller
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


    private function getCart()
    {
        return Cart::where('user_id', Auth::id())->first();
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
