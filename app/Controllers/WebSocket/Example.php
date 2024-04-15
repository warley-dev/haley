<?php

namespace App\Controllers\WebSocket;

use Haley\Collections\Log;
use Haley\Console\Lines;
use Haley\WebSocket\InterfaceSocketClient;
use Haley\WebSocket\SocketClient;

use Throwable;

class Example extends Lines implements InterfaceSocketClient
{
    public function onOpen(SocketClient $client)
    {
    
    } 

    public function onMessage(mixed $message, int $message_id, SocketClient $client)
    {     
      
    }

    public function onClose(SocketClient $client)
    {
      
    }

    public function onError(string $on, SocketClient $client, Throwable $error)
    {
        $this->red('[error on ' . $on . ' - ' . $client->id() . '] : ' . $error->getMessage())->br();

        Log::create('websocket', $error->getMessage());
    }
}
