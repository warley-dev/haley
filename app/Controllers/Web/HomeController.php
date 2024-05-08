<?php

namespace App\Controllers\Web;

use App\Controllers\Controller;
use DateTime;
use Haley\Collections\Log;
use Haley\Database\DB;

class HomeController extends Controller
{
    public function index()
    {
        $filmes = DB::table('filmes')->limit(5000)->get();

        // dd($filmes);

        return view('home', [
            'filmes' => $filmes
        ]);
    }
}
