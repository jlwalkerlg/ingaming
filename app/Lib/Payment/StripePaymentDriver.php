<?php

namespace App\Lib\Payment;

use Stripe\Stripe;
use Stripe\Error\Base;
use Stripe\PaymentIntent;
use App\Models\Transaction;

class StripePaymentDriver implements PaymentContract
{
    /** @var array $data Data. */
    private $data;

    /** @var int $cartId Cart ID. */
    private $cartId;

    /** @var Transaction $transaction Transaction. */
    private $transaction;


    public function __construct()
    {
        Stripe::setApiKey(STRIPE_SECRET_KEY);
    }


    public function renderCheckout(array $products)
    {
        $subtotal = array_reduce($products, function ($carry, $item) {
            return $carry + ($item->price * $item->quantity);
        });

        $data['title'] = 'Checkout';
        $data['products'] = $products;
        $data['subtotal'] = $subtotal;
        $data['scripts'] = ['checkout.stripe'];

        render('checkout/stripe', $data);
    }


    public function setData(array $data)
    {
        $this->data = $data;
    }


    public function setCartId($cartId)
    {
        $this->cartId = (int)$cartId;
    }


    public function saveTransaction($intent)
    {
        if ($intent->status === 'requires_confirmation') {
            $transaction = new Transaction;
            $transaction->assign($this->data);
            $transaction->gateway = 'stripe';
            $transaction->amount = $intent->amount / 100;
            $transaction->status = 'pending';
            $transaction->payment_id = $intent->id;
            $transaction->address_1 = $this->data['address']['line1'];
            $transaction->address_2 = $this->data['address']['line2'];
            $transaction->city = $this->data['address']['city'];
            $transaction->country = $this->data['address']['country'];
            $transaction->postcode = $this->data['address']['postal_code'];
            $transaction->cart_id = $this->cartId;
        } elseif ($intent->status === 'succeeded') {
            $transaction = Transaction::where('payment_id', $intent->id)->first();
            $transaction->status = 'succeeded';
        }
        $this->transaction = $transaction;
        return $transaction->save();
    }


    public function confirmPayment($subtotal)
    {
        try {
            // Create a new PaymentIntent.
            if (isset($this->data['payment_method_id'])) {
                $intent = PaymentIntent::create([
                    'payment_method' => $this->data['payment_method_id'],
                    'amount' => $subtotal * 100,
                    'currency' => 'gbp',
                    'confirmation_method' => 'manual'
                ]);
                // Save new intent in transactions table.
                $this->saveTransaction($intent);
            } elseif (isset($this->data['payment_intent_id'])) {
                // Retrieve an existing PaymentIntent from those
                // whose cards require additional auth processing,
                // e.g. 3D Secure.
                $intent = PaymentIntent::retrieve(
                    $this->data['payment_intent_id']
                );
            }
            $intent->confirm();
            $this->intent = $intent;
            return $this->generatePaymentResponse($intent);
        } catch (Base $e) {
            // Display error on client
            return [
                'error' => $e->getMessage(),
                'fatal' => false
            ];
        }
    }


    private function generatePaymentResponse(PaymentIntent $intent)
    {
        if (
            $intent->status === 'requires_action' &&
            $intent->next_action->type === 'use_stripe_sdk'
        ) {
            // Payment requires additional actions, e.g. for cards
            // using 3D Secure auth.
            return [
                'requires_action' => true,
                'payment_intent_client_secret' => $intent->client_secret
            ];
        } else if ($intent->status === 'succeeded') {
            // The payment didn’t need any additional actions and completed!
            // Handle post-payment fulfillment.
            // Update intent in database with status = success.
            $this->saveTransaction($intent);
            return [
                'success' => true
            ];
        } else {
            // Invalid status.
            http_response_code(500);
            return [
                'error' => 'Invalid PaymentIntent status.',
                'fatal' => true
            ];
        }
    }

    public function getTransaction()
    {
        return $this->transaction;
    }
}
