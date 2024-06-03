<?php

use Haley\Server\Server;

// --------------------------------------------------------------------------|
//                           SERVERS ROUTES                                  |
// --------------------------------------------------------------------------|


Server::ws('framework', 6000, 'App\Controllers\Server\Teste')->name('teste');

// rename channels
// Websocket::channel(6001, [Chat::class])->host('framework')->name('chat');
// Websocket::channel(2030, [Streaming::class])->host('framework')->name('streaming');
