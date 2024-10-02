<?php

namespace App\Controllers\Api;

class Example
{
    public function create()
    {
        return response()->json([]);
    }

    public function update()
    {
        return response()->json([]);
    }

    public function delete()
    {
        return response()->json([]);
    }

    public function search()
    {
        return dd(request()->headers());

        return response()->json([]);
    }
}
