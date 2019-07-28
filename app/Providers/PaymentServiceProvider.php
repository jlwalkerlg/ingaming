<?php

namespace App\Providers;

use Bellona\Support\ServiceProvider;
use App\Lib\Payment\BraintreePaymentDriver;
use App\Lib\Payment\StripePaymentDriver;
use App\Lib\Payment\Payment;

class PaymentServiceProvider extends ServiceProvider
{
    public $defer = true;

    public $services = [Payment::class];

    public function register()
    {
        require_once PROJECT_ROOT . '/config/payment.php';

        $this->app->singleton(Payment::class, function ($app) {
            switch (PAYMENT_DRIVER) {
                case 'braintree':
                    $driver = new BraintreePaymentDriver;
                    break;
                case 'stripe':
                default:
                    $driver = new StripePaymentDriver;
            }
            return new Payment($driver);
        });
    }
}
