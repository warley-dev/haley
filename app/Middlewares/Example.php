<?php

namespace App\Middlewares;

use Haley\Router\Middleware;

class Example extends Middleware
{
    public function example()
    {
        if (request()->session()->has('example')) return;

        return $this->abort(403);
    }
}
