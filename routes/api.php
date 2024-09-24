<?php

use Haley\Router\Route;

// --------------------------------------------------------------------------|
//                               API ROUTES                                  |
// --------------------------------------------------------------------------|

Route::error('App\Controllers\Api\ErrorController::response')->group(function () {

    Route::post('request', function () {
        return response()->json(request()->all());
    });

    Route::patch('method', function () {
        return [
            'a' => 1,
            'b' => 2
        ];
    });

    Route::name('api')->namespace('App\Controllers\Api')->group(function () {
        // Route::get('/dgfh', 'WelcomeController::welcome')->name('welcome');

        Route::post('/', function () {
            return response()->json(request()->all());
        });
    });
});
