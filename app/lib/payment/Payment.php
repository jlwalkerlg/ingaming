<?php

namespace App\Lib\Payment;

use App\Lib\Payment\PaymentContract;

class Payment
{
    public function __construct(PaymentContract $driver)
    {
        $this->driver = $driver;
    }


    public function __call($name, $arguments)
    {
        return $this->driver->$name(...$arguments);
    }
}
