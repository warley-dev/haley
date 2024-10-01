<?php

namespace App\Controllers\Server;

use Haley\Server\WebSocket\WebSocket;
use Haley\Server\Timer;
use Throwable;

class Stream
{
    public function onHandshake(int $id, array $params, array $header, WebSocket $ws)
    {
        return true;
    }

    public function onOpen(int $id, array $params, array $header, WebSocket $ws)
    {
        $ws->send($id, json_encode([
            'message' => 'connected'
        ]));
    }

    public function onMessage(int $id, string $data, WebSocket $ws, bool $binary)
    {
        // ...
    }

    public function onClose(int $id, WebSocket $ws)
    {
        // ...
    }

    public function onError(string $on, Throwable $error, WebSocket $ws)
    {
        // ...
    }

    public function timer(Timer $timer, WebSocket $ws)
    {
        // $timer->setInterval(1000, function ($id) use ($ws, $timer) {});

        // $id = $timer->setTimeout(5000, function () use ($ws, $timer) {

        // });
    }
}
