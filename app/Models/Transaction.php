<?php

namespace App\Models;

use Bellona\Database\Model;

class Transaction extends Model
{
    protected $table = 'transactions';
    protected $fillable = ['gateway', 'type', 'payment_id', 'amount', 'status', 'card_brand', 'last4', 'name', 'email', 'address_1', 'address_2', 'city', 'country', 'postcode', 'user_id', 'cart_id'];
}
