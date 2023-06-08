<?php

use App\Models\clientes;
use App\Models\filmes;
use Haley\Database\Query\DB;
use Haley\Router\Route;

// --------------------------------------------------------------------------|
//                               WEB ROUTES                                  |
// --------------------------------------------------------------------------|

Route::namespace('App\Controllers\Web')->name('web')->group(function () {

    Route::get('/', function () {
       dd();

















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
