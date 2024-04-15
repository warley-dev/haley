<?php
namespace App\Middlewares;

use Haley\Router\Middleware;

class Auth extends Middleware
{
    public function test()
    {
        // return $this->error(403);
        return $this->continue();
    }

    public function admin()
    {
        if (request()->session()->has('admin')) {            
            return $this->continue();           
        }   

        return $this->abort();
    }

    public function adminFile()
    {
        if (request()->session()->has('admin')) {            
            return $this->continue();           
        }     
            
        return $this->abort();
    }

    public function user()
    {
        if (request()->session()->has('user')) {
            return $this->continue();
        } 
     
        return $this->abort();
    }

    public function userFile()
    {
        if (request()->session()->has('user')) {            
            return $this->continue();           
        }
       
        return $this->abort();
    }
}