<?php

namespace App\Middleware;

use Bellona\Support\Facades\Auth;
use Bellona\Support\Facades\Session;
use Bellona\Http\Request;

class Admin
{
    public function run(Request $request)
    {
        if (!Auth::isLoggedIn() || !Auth::user()->isAdmin()) {
            http_response_code(403);
            Session::flash('alert', 'Access denied.');
            back();
        }
    }
}
