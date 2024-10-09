<?php

use App\Controllers\Web\Home;
use App\Models\users;
use Haley\Collections\Memory;
use Haley\Database\DB;
use Haley\Kernel;
use Haley\Router\Route;

// --------------------------------------------------------------------------|
//                               WEB ROUTES                                  |
// --------------------------------------------------------------------------|

Route::namespace('App\Controllers\Web')->name('web')->group(function () {
    Route::get('/', [Home::class, 'index'])->name('home');
    Route::view('view', 'test');

    // unificar classe
    Route::get('model', function () {
        // aidicionar and nas querys join
        // users::query()->join('acesso','users.id_acesso', 'acesso.id',[]);



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

    Route::get('session', function () {



        dd(ini_get('upload_max_filesize'));

        request()->session()->set('user', [
            'token' => 'aaaaaaa'
        ]);

        dd(request()->session('user'), request()->session()->expire());
    })->name('session');

    Route::get('tests', function () {
        // dd(Memory::$memories);

        Kernel::setMemory('test.um.dois.tres', 'aaaaaaa');

        Kernel::setMemory('test.um.dois.outrodois', 'dois value');

        Kernel::setMemory('routes.web', 'dois value');


        // Kernel::unsetMemory('test.um.dois.tres');

        dd(Kernel::$memories, Kernel::unsetMemory('test.um.dois.tres'), Kernel::$memories);

        // Kernel::onTerminate(function() {

        // });

    })->name('tests');

    Route::get('route/{um?}/{dois?}/{tres?}', function () {
        // ...
        echo 'get';
    })->name('route');

    Route::post('route/{um?}/{dois?}/{tres?}', function () {
        // ...
        echo 'post';
    })->name('route');






});
