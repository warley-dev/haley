<?php

namespace App\Controllers\Web;

use App\Controllers\Controller;
use Haley\Database\DB;

class DatabaseController extends Controller
{
    public function index()
    {






        // dd(DB::table(''));



        // DB::scheme()->constraint()->create('');




        $test = DB::table('users')->select([
            'users.*',
            'admin_access.name as access_name'
        ]);

        $test->where('users.active', 1);

        $test->join('admin_access', 'users.id_access', 'admin_access.id');

        dd($test->get());
    }
}
