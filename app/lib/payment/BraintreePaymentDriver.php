<?php

namespace App\Lib\Payment;

use Braintree\Gateway;
use App\Models\Transaction;

class BraintreePaymentDriver implements PaymentContract
{
    /** @var Gateway $gateway Braintree Gateway instance. */
    private $gateway;

    /** @var array $data Data. */
    private $data;

    /** @var int $cartId Cart ID. */
    private $cartId;

    /** @var Transaction $transaction Transaction. */
    private $transaction;


    public function __construct()
    {
        $this->gateway = new Gateway([
            'environment' => getenv('BT_ENVIRONMENT'),
            'merchantId' => getenv('BT_MERCHANT_ID'),
            'publicKey' => getenv('BT_PUBLIC_KEY'),
            'privateKey' => getenv('BT_PRIVATE_KEY')
        ]);
    }


    public function renderCheckout(array $products)
    {
        $subtotal = array_reduce($products, function ($carry, $item) {
            return $carry + ($item->price * $item->quantity);
        });

        $data['title'] = 'Checkout';
        $data['products'] = $products;
        $data['subtotal'] = $subtotal;
        $data['clientToken'] = $this->gateway->ClientToken()->generate();
        $data['scripts'] = ['checkout.braintree'];

        render('checkout/braintree', $data);
    }


    public function saveTransaction($bt)
    {
        $transaction = new Transaction;

        $transaction->gateway = 'braintree';
        $transaction->type = $bt->type;
        $transaction->payment_id = $bt->id;
        $transaction->amount = $bt->amount;
        $transaction->status = $bt->status;
        $transaction->card_brand = $bt->creditCardDetails->cardType;
        $transaction->last4 = $bt->creditCardDetails->last4;
        $transaction->name = $this->data['name'];
        $transaction->email = $this->data['email'];
        $transaction->address_1 = $this->data['address']['line1'];
        $transaction->address_2 = $this->data['address']['line2'];
        $transaction->city = $this->data['address']['city'];
        $transaction->country = $this->data['address']['country'];
        $transaction->postcode = $this->data['address']['postal_code'];
        $transaction->cart_id = $this->cartId;

        $this->transaction = $transaction;
        return $transaction->save();
    }


    public function confirmPayment($subtotal)
    {
        $nonce = $this->data['payment_method_nonce'];

        $result = $this->gateway->transaction()->sale([
            'amount' => $subtotal,
            'paymentMethodNonce' => $nonce,
            'options' => [
                'submitForSettlement' => true
            ]
        ]);

        if ($result->success || !is_null($result->transaction)) {
            $transaction = $result->transaction;
            $this->saveTransaction($transaction);

            return [
                'success' => true
            ];
        } else {
            $errorString = "";
            foreach ($result->errors->deepAll() as $error) {
                $errorString .= 'Error: ' . $error->code . ": " . $error->message . "\n";
            }
            return [
                'error' => $errorString
            ];
        }
    }


    public function getTransaction()
    {
        return $this->transaction;
    }


    public function setData(array $data)
    {
        $this->data = $data;
    }


    public function setCartId($cartId)
    {
        $this->cartId = (int)$cartId;
    }
}
