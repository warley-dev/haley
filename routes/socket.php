<?php


use App\Controllers\Socket\TesteController;
use Haley\Router\Websocket;

// --------------------------------------------------------------------------|
//                            WEBSOCKET ROUTES                               |
// --------------------------------------------------------------------------|

Websocket::ws(9058, [TesteController::class])->host('framework')->name('teste')->usleep(1000);