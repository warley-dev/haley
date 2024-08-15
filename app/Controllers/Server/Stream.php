<?php

namespace App\Controllers\Server;

use Haley\Server\WebSocket\WebSocket;
use Haley\Server\Timer;
use Haley\Shell\Shell;
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
        // $file = directoryRoot('private/stream.mp4');

        // if (file_exists($file)) unlink($file);

        // $data = json_encode([
        //     'code' => 'connected'
        // ]);

        // $ws->send($id,$data);
    }

    public function onMessage(int $id, string $data, WebSocket $ws, bool $binary)
    {
        // dd(json_decode($data, true));

      $hls_dir = directoryRoot('private/hls');

        if(is_dir($hls_dir)) {
            deleteDir($hls_dir);

            createDir($hls_dir);
        }

        $file = directoryRoot('private/stream.mp4');
        $out = directoryRoot('private/hls/output.m3u8');

        Shell::exec("ffmpeg -i {$file} -codec: copy -start_number 0 -hls_list_size 0 -f hls {$out}");


        if ($binary) {
            if (!file_exists($file)) {
                file_put_contents($file, $data);
            } else {
                file_put_contents($file, $data, FILE_APPEND);
            }
        }

        touch($file);

        // $ws->close($id);
        // dd($ws->clients());
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

        $timer->setInterval(5000, function ($id) use ($ws, $timer) {
            if (!$ws->connections()) {

            }
        });

        // $id = $timer->setTimeout(5000, function () use ($ws) {
        //     dd($ws->clients());
        // });
    }
}
