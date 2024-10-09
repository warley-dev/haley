<?php

use Haley\Router\Route;

// --------------------------------------------------------------------------|
//                               API ROUTES                                  |
// --------------------------------------------------------------------------|

Route::namespace('App\Controllers\Api')->group(function () {
    Route::get('search', 'Example@search');

    Route::post('test', function () {
        return request()->headers();
    });
});
