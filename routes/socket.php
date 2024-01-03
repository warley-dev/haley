<?php


use App\Controllers\Socket\TesteController ;
use Haley\Router\Websocket;

// --------------------------------------------------------------------------|
//                            WEBSOCKET ROUTES                               |
// --------------------------------------------------------------------------|

Websocket::ws('teste',[TesteController::class])->name('teste')->domain('google');
