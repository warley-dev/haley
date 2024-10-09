<?php

namespace App\Middlewares;

use Haley\Router\Middleware;

class Routes extends Middleware
{
    public function web()
    {
        // ...
    }

    public function api()
    {
        response()->header('Access-Control-Allow-Origin', '*');
        response()->header('Content-type', 'application/json; charset=utf-8');
    }
}
