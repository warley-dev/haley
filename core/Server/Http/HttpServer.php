<?php

namespace Haley\Server\Http;

// use Haley\Server\Timer;
use Haley\Shell\Shell;
use Swoole\Coroutine\Channel;
use Swoole\Coroutine\System;
use Swoole\Async;
// use Swoole\Server;
// use Swoole\Timer;
use Throwable;

use Swoole\Http\Server;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Timer;

class HttpServer
{
    protected Server|null $server = null;

    public function run(array $params)
    {
        // Cria o servidor Swoole TCP

        // dd($params);


        // $socketFile = directoryRoot('private/stream.mp4');

        // Certifique-se de que o arquivo não existe antes de criar o servidor
        // if (file_exists($socketFile)) {
        //     unlink($socketFile);
        // }

        // $server = new Server($socketFile, $params['port'], SWOOLE_PROCESS, SWOOLE_SOCK_UNIX_STREAM); // local
        // $server = new Server($params['host'], $params['port'], SWOOLE_PROCESS, SWOOLE_UNIX_STREAM);

        // $server->set(array(
        //     'reactor_num'   => 2,     // number of threads
        //     'worker_num'    => 4,     // number of processes
        //     'backlog'       => 128,   // set the length of the listen queue
        //     'max_request'   => 50,    // maximum number of requests per process
        //     'dispatch_mode' => 1,     // data packet dispatch strategy
        // ));

        // $server->on('connect', function (Server $server, int $fd, int $reactorId) {
        //     Shell::green("Conectado: {$fd}")->br();
        // });

        // $server->on('receive', function (Server $server, int $fd, int $reactorId, string $data) {
        //     Shell::green("receive: {$fd}")->br();

        //     dd($data);
        // });

        // $server->on('request', function ($request, $response) {
        //     Shell::green("request: {$request->fd}")->br();
        // });

        // $server->on('close', function (Server $server, int $fd) {
        //     Shell::green("close: {$fd}")->br();
        // });





        // Evento quando o servidor recebe uma conexão
        // $server->on("connect", function (Server $server, int $fd) {
        //     echo "Novo cliente conectado: {$fd}\n";
        // });

        // // Evento quando o servidor recebe dados
        // $server->on("receive", function (Server $server, int $fd, int $reactorId, string $data) {
        //     // Suponha que os dados sejam o caminho do arquivo de vídeo
        //     $videoFile = 'path/to/video/file.mp4';
        //     $fileHandle = fopen($videoFile, 'rb');

        //     while (!feof($fileHandle)) {
        //         $data = fread($fileHandle, 1024);
        //         $server->send($fd, $data);
        //     }

        //     fclose($fileHandle);
        //     $server->close($fd);
        // });

        // // Evento quando a conexão é fechada
        // $server->on("close", function (Server $server, int $fd) {
        //     echo "Cliente desconectado: {$fd}\n";
        // });

        // Inicia o servidor
        // $server->start();

        $clients = [];

        $this->server = new Server($params['host'], $params['port']);

        $this->server->set([
            'task_worker_num' => 1, // Número de trabalhadores de tarefa
        ]);


        $this->server->on("start", function (Server $server) {
            echo "Swoole HTTP server is started";
        });

        $this->server->on("request", function ($request, Response $response) {
            $path = directoryRoot('private/stream.mp4');

            if (!file_exists($path)) {
                $response->status(404);
                $response->end("Stream not found");
                return;
            }

            $this->stream($path, $response, $request);
        });

        $this->server->on('Task', function (Server $server, $taskId, $workerId, $data) {
        });

        $this->server->on('close', function (Server $server, int $fd) {
            dd('close');
        });

        $this->server->start();
    }

    protected function stream(string $path,  Response $response, Request $request)
    {

        // $response->header('Accept-Ranges', 'bytes');

        // $response->header('Transfer-Encoding', 'chunked');
        // $response->header('Connection', 'keep-alive');
        // $response->header('Token', 'heloword');


        // $response->header("Access-Control-Allow-Origin", "*");
        // $response->header("Access-Control-Allow-Methods", "GET, POST, PUT, DELETE, OPTIONS");
        // $response->header("Access-Control-Allow-Headers", "Content-Type, Authorization, X-Requested-With");

        $path = directoryRoot('private/hls/output.m3u8');
        // $response->header('Content-Type', mime_content_type($path));
        // $response->status(206);

        $response->write(file_get_contents($path));

        // usleep(4000000);

        // Shell::blue('Stream ended')->br();

        // $length = 1024 * 1024;
        // $size = 0;

        // while (file_exists($path)) {
        //     if (filesize($path) > $size + $length) {
        //         dd('send');

        //         $response->status(206);

        //         $send = $response->write(file_get_contents($path, false, null, $size, $length));

        //         if ($send) $size += $length;
        //     }

        //     usleep(10000);

        //     clearstatcache(true, $path);
        // }







        // usleep(2000000);

        // Shell::blue('Stream ended')->br();



        // Set the response headers
        // $response->header('Content-Type', 'video/mp4');
        // $response->header('Accept-Ranges', 'bytes');

        // // Check if there is a range request
        // if (isset($request->header['range'])) {
        //     Shell::red($request->header['range'])->br();

        //     $range = $request->header['range'];
        //     $range = str_replace('bytes=', '', $range);
        //     list($start, $end) = explode('-', $range);

        //     dd($range);

        //     $filesize = filesize($path);
        //     $end = $end ? (int)$end : ($filesize - 1);
        //     $length = $end - $start + 1;

        //     $response->header('Content-Length', $length);
        //     $response->header('Content-Range', "bytes {$start}-{$end}/{$filesize}");
        //     $response->status(206);

        //     $handle = fopen($path, 'rb');
        //     fseek($handle, $start);
        //     $data = fread($handle, $length);
        //     fclose($handle);
        // } else {
        //     Shell::green('completo')->br();
        //     $filesize = filesize($path);
        //     $response->header('Content-Length', $filesize);
        //     $data = file_get_contents($path);
        // }

        // $response->end($data);



































        // $dt_created = filectime($path);
        // $dt_edited = null;

        // $size = 0;

        // while (file_exists($path) and $dt_created === filectime($path)) {
        //     clearstatcache(true, $path);

        //     if ($dt_edited !== null and $dt_edited == filemtime($path)) continue;

        //     dd('task send');

        //     $new_size = filesize($path);

        //     if ($new_size > $size) {
        //         // $sent = $response->sendfile($path, $size, $new_size - $size);

        //         $sent = $response->write(file_get_contents($path, false, null, $size, $new_size - $size));

        //         dd('enviado:' . formatSize($size) . ' --- ' . formatSize($new_size - $size));

        //         if ($sent) $size = $new_size;


        //     } else {
        //         $response->header('Content-Type', 'video/mp4');
        //         $response->header('Transfer-Encoding', 'chunked');
        //         $response->header('Connection', 'keep-alive');
        //     }

        //     usleep(2000000);
        // }
    }
}
