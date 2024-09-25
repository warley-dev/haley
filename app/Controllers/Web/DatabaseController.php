<?php

namespace App\Controllers\Web;

use App\Controllers\Controller;
use Haley\Database\DB;

class DatabaseController extends Controller
{
    public function index()
    {
        $data = [
            'int' => 1,
            'varchar' => '',
            'text' => '',
            'json' => null,
            // 'timestamp' => '',
            // 'date' => '',
            // 'datetime' => '',
            // 'year' => '',
            // 'time' => '',
            'double' => null,
            'float' => null,
            'decimal' => null,
            'boolean' => null,
            'set' => null,
            'enum' => null,
            'nome' => 'helo word',
            'email' => null,
            // 'created_at' => '',
            // 'update_at' => ''
        ];

        DB::table('outro')->insert($data);
        DB::table('test')->insert($data);

        dd(DB::table('outro')->get(), DB::table('test')->get());



        // dd(DB::table(''));



        // DB::scheme()->constraint()->create('');




        // $test = DB::table('users')->select([
        //     'users.*',
        //     'admin_access.name as access_name'
        // ]);

        // $test->where('users.active', 1);

        // $test->join('admin_access', 'users.id_access', 'admin_access.id');

        // dd($test->get());
    }
}
