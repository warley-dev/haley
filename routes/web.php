<?php

use App\Controllers\Web\Home;
use App\Models\users;
use Haley\Collections\Hash;
use Haley\Kernel;
use Haley\Router\Route;

// --------------------------------------------------------------------------|
//                               WEB ROUTES                                  |
// --------------------------------------------------------------------------|

Route::namespace('App\Controllers\Web')->name('web')->group(function () {
    Route::get('/', [Home::class, 'index'])->name('home');

    Route::get('test', function () {
        $text = 'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32.The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.';

        $encriptado = Hash::encrypt($text, '12345789');

        dd($encriptado, Hash::decrypt($encriptado, '12345789'));
    });

    // Route::view('view', 'test');

    // // criar pastas de chache automaticamente

    // // unificar classe
    // Route::get('model', function () {
    //     // aidicionar and nas querys join
    //     // users::query()->join('acesso','users.id_acesso', 'acesso.id',[]);



    //     $select = users::updateOrCreate(['email' => 'new2@hotmail.com'], [
    //         'email' => 'new2@hotmail.com'
    //     ]);

    //     dd($select);
    // });

    // Route::get('database', function () {});

    // Route::get('route', function () {
    //     // refatorar CONSTANTES route verificar middlewares e criar classe de variaveis do framework
    //     // adicionar opcao route no middlewares
    // })->name('route');

    // Route::get('session', function () {



    //     dd(ini_get('upload_max_filesize'));

    //     request()->session()->set('user', [
    //         'token' => 'aaaaaaa'
    //     ]);

    //     dd(request()->session('user'), request()->session()->expire());
    // })->name('session');

    // Route::get('tests', function () {
    //     // dd(Memory::$memories);

    //     Kernel::setMemory('test.um.dois.tres', 'aaaaaaa');

    //     Kernel::setMemory('test.um.dois.outrodois', 'dois value');

    //     Kernel::setMemory('routes.web', 'dois value');


    //     // Kernel::unsetMemory('test.um.dois.tres');

    //     dd(Kernel::$memories, Kernel::unsetMemory('test.um.dois.tres'), Kernel::$memories);

    //     // Kernel::onTerminate(function() {

    //     // });

    // })->name('tests');

    // Route::get('route/{um?}/{dois?}/{tres?}', function ($u) {
    //     dd(route()->params(),route()->name('web.route',['aaaa']));

    // })->name('route');

    // Route::post('route/{um?}/{dois?}/{tres?}', function () {
    //     // ...
    //     echo 'post';
    // })->name('route');

    // Route::view('post','post');

    // Route::post('post', function() {
    //     dd(request()->all());
    // })->name('post');
});
