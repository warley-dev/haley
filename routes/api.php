<?php

use Haley\Router\Route;

// --------------------------------------------------------------------------|
//                               API ROUTES                                  |
// --------------------------------------------------------------------------|

Route::namespace('App\Controllers\Api')->name('api')->group(function () {
    Route::get('example', function () {
        return json_encode([
            'message' => 'Helo word'
        ]);
    });
});
