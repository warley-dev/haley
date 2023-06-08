<?php

use Haley\Database\Query\DB;
use Haley\Router\Route;

// --------------------------------------------------------------------------|
//                               WEB ROUTES                                  |
// --------------------------------------------------------------------------|

Route::namespace('App\Controllers\Web')->name('web')->group(function () {

    Route::get('/', function () {
      
   

    $query = DB::table('filmes')->where('id','>',16)->limit(15)->all();

    dd($query);





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
