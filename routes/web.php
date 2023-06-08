<?php

use Haley\Database\Query\DB;
use Haley\Http\Route as HttpRoute;
use Haley\Router\Route;

// --------------------------------------------------------------------------|
//                               WEB ROUTES                                  |
// --------------------------------------------------------------------------|

Route::namespace('App\Controllers\Web')->name('web')->group(function () {

    Route::view('/','view',['filmes' => DB::table('filmes')->limit(20)->all()])->name('view');
















    // Route::get('/','HomeController@index')->name('home');

    // Route::get('/', function () {
        
    // })->name('home');


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
