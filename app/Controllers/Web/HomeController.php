<?php

namespace App\Controllers\Web;

use App\Controllers\Controller;
use DateTime;
use Haley\Collections\Log;
use Haley\Database\Query\DB;

class HomeController extends Controller
{
    public function index()
    {
        $filmes = DB::table('filmes')->limit('15')->all();     
        
        $array = [15,31,54];


        
        dd(in_array(150,$array));

        return view('home', [
            'filmes' => $filmes
        ]);
    }
}
