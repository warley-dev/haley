<?php

use Haley\Database\Query\DB;
use Haley\Router\Route;

// --------------------------------------------------------------------------|
//                               WEB ROUTES                                  |
// --------------------------------------------------------------------------|

Route::namespace('App\Controllers\Web')->name('web')->group(function () {

    Route::get('/', function () {

        // dd(DB::query('SELECT * FROM `filmes` WHERE `id` = 5 OR (`id` = 1 OR `id` = 2 ) LIMIT 15')->fetchAll());

        $query = DB::table('filmes');

        $query->where('genero', 'LIKE', '%terror%');

        $query->whereCompact(function () use ($query) {
            $query->where('id', 1);
            $query->orWhere('titulo', 'A Carruagem Fantasma');

            $query->whereCompact(function() use ($query) {
                $query->where('id', 1);
            });
        });


        dd($query->limit(15)->getQuery());
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
