<?php
namespace App\Middlewares;

use Haley\Router\Middleware;

class Routes extends Middleware
{
    public function web()
    {
        // ...
      
        return $this->continue();
    }

    public function api()
    {
        response()->header('Access-Control-Allow-Origin', '*');            
    
        $this->continue();
    } 
}
