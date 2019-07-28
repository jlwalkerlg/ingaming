<?php

namespace App\Middleware;

use Bellona\Http\Request;
use Bellona\Support\Facades\Auth;
use Bellona\Support\Facades\Session;

class Authenticate
{
    public function run(Request $request)
    {
        if (!Auth::isLoggedIn()) {
            $this->deny();
        }
    }


    private function deny()
    {
        Session::flash('alert', 'Must be logged in.');
        redirect(URL_ROOT);
    }
}
