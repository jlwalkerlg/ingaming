<?php

namespace App\Models;

use Bellona\Database\Model;
use Bellona\Support\Facades\DB;

class Cart extends Model
{
    protected $table = 'carts';
    protected $fillable = ['user_id'];

    public function products()
    {
        return DB::table('cart_products as cp')->join('games', 'cp.game_id', '=', 'games.id')->join('platforms', 'games.platform_id', '=', 'platforms.id')->where('cart_id', $this->id)->select('games.id', 'games.title', 'games.price', 'cp.quantity', 'games.case_img', 'games.cover_img', 'platforms.short_name as platform')->get();
    }


    public function subtotal()
    {
        $sql = 'SELECT SUM(games.price * cp.quantity)
        FROM cart_products as cp
        INNER JOIN games ON cp.game_id = games.id
        WHERE cp.cart_id = ?
        ';
        $params = [$this->id];

        $sth = DB::query($sql, $params);
        $sth->execute();

        return $sth->fetchColumn();
    }
}
