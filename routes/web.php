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

        dd(DB::scheme()->column()->rename('new', 'text', 'teste'));

        $count = 1;
        $data = [];

        DB::table('teste')->delete();

        while ($count < 86) {
            $data[] = [
                'id' => $count,
                'nome' => 'test'
            ];

            $count++;
        }

        DB::table('teste')->insert($data);

        $query = DB::table('teste');

        // $query->whereCompact(function () use ($query) {
        //     // $query->where('id', 1);
        //     // $query->orWhere('id', 2);
        // });


        $query->orderByAsc('id');
        $query->limit(3, 29);

        dd($query->getQuery(), $query->get());


        // dd(DB::connection('pgsql'));
        // dd(DB::connection('mariadb'));
    });

    Route::get('route', function () {
        // refatorar CONSTANTES route verificar middlewares e criar classe de variaveis do framework
        // adicionar opcao route no middlewares
    })->name('route');
});
