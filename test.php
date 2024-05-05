<?php

// reescrever websockets:
// mudar para server: Server::ws() ....

// https://wiki.swoole.com/en/#/server/methods

class Clients
{
    public static array $clients = [];
};

// Create a WebSocket Server object and listen on 0.0.0.0:9502.
$ws = new Swoole\WebSocket\Server('framework', 6001);


// Listen to the WebSocket connection open event.
$ws->on('Open', function ($ws, $request) {
    Clients::$clients[$request->fd] = true;

    $ws->push($request->fd, "hello, welcome\n");


});

// Listen to the WebSocket message event.
$ws->on('Message', function ($ws, $frame) {
    // var_dump($ws);
    // var_dump($frame);
    // $ws->push($frame->fd, $frame->data);



    if ($frame->opcode == WEBSOCKET_OPCODE_BINARY) {
        // Trate o blob recebido
        $blobData = $frame->data;

        // Envie o blob de volta para o cliente

        // foreach ($this->server->connections as $fd)

        foreach (Clients::$clients as $fd => $value) $ws->push($fd, $blobData, WEBSOCKET_OPCODE_BINARY);
    } else {
        // Caso não seja um blob, trate conforme necessário
        $ws->push($frame->fd, "Apenas blobs são suportados neste exemplo.");
    }
});

// Listen to the WebSocket connection close event.
$ws->on('Close', function ($ws, $fd) {
    unset(Clients::$clients[$fd]);
    var_dump($ws);
    echo "client-{$fd} is closed\n";
});

$ws->start();
