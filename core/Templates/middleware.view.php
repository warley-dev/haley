<?php


namespace App\Middlewares{{ $namespace ? "\\$namespace" : '' }};

use Haley\Router\Middleware;

class {{$name}} extends Middleware
{
    public function example()
    {
        if (request()->session()->has('example')) return;

        return $this->abort(403);
    }
}