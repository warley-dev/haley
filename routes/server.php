<?php

use Haley\Server\Server;

// --------------------------------------------------------------------------|
//                           SERVERS ROUTES                                  |
// --------------------------------------------------------------------------|

// Server::ws('framework', 5005, 'App\Controllers\Server\Teste');

Server::namespace('App\Controllers\Server')->name('server')->group(function () {
    Server::ws(5007, 'Teste')->path('helo/{dois}/{tres}/{quatro?}')->name('chat');

    Server::ws(5012, 'Stream')->name('stream');
    // Server::stream(5010, 'Teste');
    Server::stream(6013, 'Stream')->name('stream');
});

// rename channels
// Websocket::channel(6001, [Chat::class])->host('framework')->name('chat');w
// Websocket::channel(2030, [Streaming::class])->host('framework')->name('streaming');
