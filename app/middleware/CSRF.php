<?php

namespace App\Middleware;

use Bellona\Http\Request;
use Bellona\Support\Facades\CSRF as CSRFFacade;
use Bellona\Support\Facades\Session;

class CSRF
{
    public function run(Request $request)
    {
        if ($request->isPost() && !CSRFFacade::validate()) {
            $this->deny($request);
        }
    }


    private function deny(Request $request)
    {
        Session::flash('alert', 'Something went wrong with the request.');
        $request->save();
        back();
    }
}
