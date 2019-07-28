<?php

namespace App\Models;

use Bellona\Database\Model;

class User extends Model
{
    protected $table = 'users';
    protected $fillable = ['first_name', 'last_name', 'email', 'password', 'cart_id'];


    /**
     * Authorization helper.
     *
     * @param string $method Name of method to run on relevant policy class.
     * @param mixed $model Model instance, or name of model class.
     * @return bool True if authorized; false otherwise.
     */
    public function can(string $method, $model)
    {
        return can($method, $model, $this);
    }


    /**
     * Admin authorization helper.
     */
    public function isAdmin()
    {
        return (int)$this->is_admin === 1;
    }
}
