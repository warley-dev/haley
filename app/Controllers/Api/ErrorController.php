<?php

namespace App\Controllers\Api;

use App\Controllers\Controller;

class ErrorController extends Controller
{
    public function response($status, $mesage)
    {
        return response()->json([
            'status' => $status,
            'mesage' => $mesage
        ]);
    }
}
