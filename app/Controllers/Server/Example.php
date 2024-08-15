<?php

namespace App\Controllers\Server;

use Haley\Server\WebSocket\WebSocket;
use Haley\Server\Timer;
use Throwable;

class Stream
{
    protected array $clients = [];
    protected string|null $stream = null;

    public function onHandshake(int $id, array $params, array $header, WebSocket $ws)
    {
        return true;
    }

    public function onOpen(int $id, array $params, array $header, WebSocket $ws)
    {
        // $ws->send($id, 'hello');
    }

    public function onMessage(int $id, string $data, WebSocket $ws, bool $binary)
    {
        // dd(json_decode($data, true));

        // $ws->close($id);
        // $ws->send($ws->clients(), $data, $binary);
    }

    public function onClose(int $id, WebSocket $ws)
    {
    }

    public function onError(string $on, Throwable $error, WebSocket $ws)
    {
        dd($on, $error->getMessage());
    }

    public function timer(Timer $timer, WebSocket $ws)
    {
        // $count = 0;

        // $timer->setInterval(1000, function ($id) use ($ws, $timer, $count) {
        //     global $count;
        //     dd($timer->info($id));
        //     $count++;
        // });

        // $id = $timer->setTimeout(5000, function () use ($ws) {
        //     dd($ws->clients());
        // });
    }
}
