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

        $select = users::query()->get();

        dd($select);
    });

    Route::get('database', function () {
        // refaturar connection database e configuracoes

        // dd(DB::connection('sqlite')->prepare('CREATE TABLE t1(a, b UNIQUE);')->execute());

        // $query = DB::connection('sqlite')->prepare('INSERT INTO t1 (`b`) VALUES(?);');

        // $query->bindValue(1,'AAAA');

        // dd($query->execute());

        dd(DB::table('t1')->connection('sqlite')->get());
    });
});
