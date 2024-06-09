<?php

use Haley\Server\Server;

// --------------------------------------------------------------------------|
//                           SERVERS ROUTES                                  |
// --------------------------------------------------------------------------|

// Server::ws('framework', 5005, 'App\Controllers\Server\Teste');

Server::namespace('App\Controllers\Server')->name('server')->group(function () {
    Server::ws(5006, 'Teste')->path('helo/{dois}/{tres}/{quatro?}')->name('1');
});


// rename channels
// Websocket::channel(6001, [Chat::class])->host('framework')->name('chat');w
// Websocket::channel(2030, [Streaming::class])->host('framework')->name('streaming');
