<?php

use App\Models\usuarios;
use Haley\Router\Route;

// --------------------------------------------------------------------------|
//                               WEB ROUTES                                  |
// --------------------------------------------------------------------------|

// Route::error(function ($status,  $message) {
//     return view('error.default',[
//         'status' => $status,
//         'message' => $message
//     ]);
// });

Route::namespace('App\Controllers\Web\\')->name('web')->group(function () {
    Route::get('/', 'HomeController@index');
    Route::view('view', 'test');

    Route::get('model', function () {
        $data = [
            [
                'id_acesso' => 1,
                'nome' => 'um',
                'email' => 'um@hotmail.com',
                'ativo' => 1
            ],

            [
                'id_acesso' => 1,
                'nome' => 'um',
                'email' => 'um@hotmail.com',
                'ativo' => 1
            ]
        ];

        $create = usuarios::createGetId($data);

        // dd($create);

        $create_or_update = usuarios::updateOrCreate(['id' => ['1', '2', '3']], [
            'nome' => 'atualizados'
        ]);

        // dd($create_or_update);

        $select = usuarios::select()->get();

        dd($select);
    });

    Route::get('test', function() {

    });
});
