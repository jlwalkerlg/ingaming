<?php

namespace App\Models;

use Bellona\Database\Model;

class CartProduct extends Model
{
    protected $table = 'cart_products';
    protected $fillable = ['cart_id', 'game_id', 'quantity'];
}
