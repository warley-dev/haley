<?php

use Haley\Server\Server;

// --------------------------------------------------------------------------|
//                           SERVERS ROUTES                                  |
// --------------------------------------------------------------------------|

// Server::ws('framework', 5005, 'App\Controllers\Server\Teste');

Server::namespace('App\Controllers\Server')->name('server')->group(function () {

    // alias usado para obter url ws
    Server::ws(5087, 'Teste')->host('framework')->path('{token?}')->name('chat');

    // Server::ws(5014, 'Stream')->name('stream');
    // Server::stream(5010, 'Teste');
    // Server::stream(6013, 'Stream')->name('stream');
});

// rename channels
// Websocket::channel(6001, [Chat::class])->host('framework')->name('chat');w
// Websocket::channel(2030, [Streaming::class])->host('framework')->name('streaming');
