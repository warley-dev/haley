<?php


use App\Controllers\Socket\TesteController;
use Haley\Router\Websocket;

// --------------------------------------------------------------------------|
//                            WEBSOCKET ROUTES                               |
// --------------------------------------------------------------------------|

Websocket::ws(8155, [TesteController::class])->host('haley.codehalley.com')->name('teste');
