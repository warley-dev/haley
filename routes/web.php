<?php

use Haley\Router\Route;

// --------------------------------------------------------------------------|
//                               WEB ROUTES                                  |
// --------------------------------------------------------------------------|

Route::namespace('App\Controllers\Web')->name('web')->group(function () {
    Route::get('/', 'HomeController::index')->name('home');

    Route::name('teste')->middleware('Auth::admin')->group(function () {
        Route::get('teste', 
        function () {
        })->name('1');
    });
});