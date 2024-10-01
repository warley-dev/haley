<?php

use Haley\Database\DB;
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

    Route::view('test', 'test');

    Route::get('haley', function () {
        dd(getMemoryUsage());
    });
});
