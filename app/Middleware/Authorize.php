<?php

namespace App\Middleware;

use Bellona\Http\Request;

class Authorize
{
    public function run(Request $request, string $action, $model)
    {
        if (!can($action, $model)) {
            $this->deny();
        }
    }


    private function deny()
    {
        http_response_code(403);
        exit('Access denied.');
    }
}
