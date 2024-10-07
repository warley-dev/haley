<?php

use App\Models\users;
use Haley\Database\DB;
use Haley\Router\Route;

// --------------------------------------------------------------------------|
//                               WEB ROUTES                                  |
// --------------------------------------------------------------------------|

Route::namespace('App\Controllers\Web\\')->name('web')->group(function () {
    Route::get('/', 'Home@index');
    Route::view('view', 'test');

    // unificar classe
    // app ini configurar

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

        // $create = users::createOrIgnore([
        //     'nome' => 'test',
        //     'email' => 'emais'
        // ]);

        // dd($create);

        // DB::table('users')->insertIgnore([
        //     'nome' => 'test'
        // ]);

        $select = users::updateOrCreate(['email' => 'new2@hotmail.com'], [
            'email' => 'new2@hotmail.com'
        ]);

        dd($select);
    });

    Route::get('database', function () {});

    Route::get('route', function () {
        // refatorar CONSTANTES route verificar middlewares e criar classe de variaveis do framework
        // adicionar opcao route no middlewares
    })->name('route');
});
