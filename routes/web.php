<?php

use App\Models\users;
use Haley\Router\Route;

// --------------------------------------------------------------------------|
//                               WEB ROUTES                                  |
// --------------------------------------------------------------------------|

Route::namespace('App\Controllers\Web\\')->name('web')->group(function () {
    Route::get('/', 'HomeController@index');
    Route::view('view', 'test');

    // unificar classe
    // app ini configurar
    // make seed
    // migration build adicionar after e before

    Route::get('model', function () {
        // $data = [
        //     [
        //         'id_acesso' => 1,
        //         'nome' => 'um',
        //         'email' => 'um@hotmail.com',
        //         'ativo' => 1
        //     ],

        //     [
        //         'id_acesso' => 1,
        //         'nome' => 'um',
        //         'email' => 'um@hotmail.com',
        //         'ativo' => 1
        //     ]
        // ];

        // $create = usuarios::createGetId($data);

        // // dd($create);

        // $create_or_update = usuarios::updateOrCreate(['id' => ['1', '2', '3']], [
        //     'nome' => 'atualizados'
        // ]);

        // dd($create_or_update);

        $select = users::query()->get();

        dd($select);
    });

    Route::get('databse', function () {
        // refaturar connection database e configuracoes
    });
});
