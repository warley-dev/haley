<?php

use Haley\Database\Query\DB;
use Haley\Router\Route;

// --------------------------------------------------------------------------|
//                               WEB ROUTES                                  |
// --------------------------------------------------------------------------|

Route::namespace('App\Controllers\Web')->name('web')->group(function () {

    Route::get('/', function () {


        // dd(DB::query('SELECT * FROM `filmes` WHERE `id` = 5 OR (`id` = 1 OR `id` = 2 ) LIMIT 15')->fetchAll());

        $select = DB::table('filmes')->where('id',500)->first();
        dd($select);

        dd(DB::table('filmes')->where('id',500));
       
    })->name('home');










    Route::get('files/teste/{name?}/{test?}', function () {
        // return response()->file(directoryPrivate('haley.png'));
        dd(route()->now());
    })->name('files');

    Route::name('teste')->middleware('Auth::admin')->group(function () {
        Route::get(
            'teste',
            function () {
            }
        )->name('1');
    });
});
