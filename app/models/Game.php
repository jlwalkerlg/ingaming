<?php

namespace App\Models;

use Bellona\Database\Model;

class Game extends Model
{
    protected $table = 'games';
    protected $fillable = ['title', 'blurb', 'description', 'platform_id', 'price', 'release_date', 'rating', 'case_img', 'cover_img', 'trailer'];


    public function isOut()
    {
        return time() >= strtotime($this->release_date);
    }
}
