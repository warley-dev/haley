<?php
namespace App\Middlewares;
use Haley\Router\Middleware;

class Api extends Middleware
{
    public function security()
    {
        response()->header('token',csrf()->token());

        return $this->continue();
    }
}