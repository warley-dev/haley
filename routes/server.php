<?php

use Haley\Server\Server;

// --------------------------------------------------------------------------|
//                           SERVERS ROUTES                                  |
// --------------------------------------------------------------------------|

Server::namespace('App\Controllers\Server')->name('server')->group(function () {
    Server::ws(5087, 'Teste')->host('framework')->path('{token?}')->name('chat');
});