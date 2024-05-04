<?php

use App\Controllers\WebSocket\Chat;
use App\Controllers\WebSocket\Streaming;
use Haley\Router\Websocket;

// --------------------------------------------------------------------------|
//                            WEBSOCKET ROUTES                               |
// --------------------------------------------------------------------------|

Websocket::channel(6001, [Chat::class])->host('framework')->name('chat');


// Websocket::channel(2030, [Streaming::class])->host('framework')->name('streaming');
