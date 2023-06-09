<?php

use Haley\Database\Query\DB;
use Haley\Router\Route;

// --------------------------------------------------------------------------|
//                               WEB ROUTES                                  |
// --------------------------------------------------------------------------|

Route::namespace('App\Controllers\Web')->name('web')->group(function () {
    Route::get('/', 'HomeController@index')->name('home');    
});

Route::prefix('test')->group(function() {

    Route::get('/',function() {
        dd(DB::helper());

        // dd(DB::helper()->createTable('teste',['id INT NOT NULL AUTO_INCREMENT PRIMARY KEY']));

        // dd(DB::helper()->setPrimaryKey('teste','id'));

        // dd(DB::helper()->getColumnSchema('teste','id'));

        // dd(DB::helper()->changeColumn('teste','helo','new_helo','int'));

        dd(DB::helper()->renameTable('rename','teste'));
    });
});