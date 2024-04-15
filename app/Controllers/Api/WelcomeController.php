<?php

namespace App\Controllers\Api;

use App\Controllers\Controller;

class WelcomeController extends Controller
{
    public function welcome()
    {
        return [
            'status' => 200,
            'mesage' => 'Welcome to API'
        ];
    }
}
