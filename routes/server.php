<?php

use Haley\Server\Server;

// --------------------------------------------------------------------------|
//                           SERVERS ROUTES                                  |
// --------------------------------------------------------------------------|

try {
    Server::namespace('App\Controllers\Server')->name('server')->group(function () {
        Server::ws('framework', 5005, 'Teste')->path('helo/{dois}/{tres}/{quatro?}')->name('1');
        // Server::ws('framework', 6000, 'Teste')->name('server-2');
    });;
} catch (\Throwable $th) {
    dd($th);
}


// rename channels
// Websocket::channel(6001, [Chat::class])->host('framework')->name('chat');w
// Websocket::channel(2030, [Streaming::class])->host('framework')->name('streaming');
