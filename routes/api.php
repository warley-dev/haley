<?php

use Haley\Router\Route;

// --------------------------------------------------------------------------|
//                               API ROUTES                                  |
// --------------------------------------------------------------------------|

Route::error('App\Controllers\Api\ErrorController::response')->group(function () {

    Route::name('api')->namespace('App\Controllers\Api')->group(function () {
        Route::get('/', 'WelcomeController::welcome')->name('welcome');
    });
});
