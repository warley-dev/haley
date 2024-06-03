<?php

namespace App\Controllers\WebSocket;

use Haley\Collections\Log;
use Haley\Console\Lines;
use Haley\WebSocket\InterfaceSocketClient;
use Haley\WebSocket\SocketClient;

use Throwable;

class Streaming extends Lines implements InterfaceSocketClient
{
    public function onOpen(SocketClient $client)
    {
        // $client->send(['mensagem' => 'Conectado'], $client->id());
    }

    public function onMessage(string $message, int $message_id, SocketClient $client)
    {
        $send_ids = $client->ids();

        // var_dump($message);

        // $this->red($message)->br()->br();

        // $message = json_decode($message, true);

        // var_dump('streaming on message: ' . $message);

        // $this->red($message)->br()->br();

        // if ($message) $client->send($message, $send_ids);
        $client->send($message, $send_ids);

        // json_encode($message);
        // $unset = array_search($client->id(), $send_ids);

        // if ($unset) unset($send_ids[$unset]);
    }

    public function onClose(SocketClient $client)
    {
    }

    public function onError(string $on, SocketClient $client, Throwable $error)
    {
        $this->red('[error on ' . $on . ' - ' . $client->id() . '] : ' . $error->getMessage() . ' - ' . $error->getFile()  . ' ' . $error->getLine())->br();

        Log::create('websocket', $error->getMessage());
    }
}
