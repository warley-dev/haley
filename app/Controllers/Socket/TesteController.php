<?php

namespace App\Controllers\Socket;

use Haley\Console\Lines;
use Haley\WebSocket\SocketController;
use Throwable;

class TesteController extends Lines
{
    public function onOpen(SocketController $socket)
    {
        $socket->send([
            'open' => 'Bem vindo ao chat'
        ], $socket->id());

        $socket->send([
            'online' => $socket->count()
        ]);
    }

    public function onMessage(mixed $message, SocketController $socket)
    {     
        $message = json_decode($message, true);     

        if ($message && $socket->id()) {
            if(!empty($message['logout'])) {
                $socket->close($socket->id());    
                return;           
            }

            $socket->setProps($socket->id(), [
                'user' => $message['user']
            ]);

            if ($socket->ip($socket->id())) $message['user'] .= ' [' . $socket->ip($socket->id()) . ']'; 
        }

        $socket->send($message, $socket->ids());
    }

    public function onClose(SocketController $socket)
    {
        if ($socket->id()) {
            $props = $socket->getProps($socket->id());

            if ($props) {
                $message = $props['user'] . ' desconectou!';

                $socket->send([
                    'disconnect' => $message,
                ], $socket->ids());
            }
        }

        $socket->send([
            'online' => $socket->count()
        ], $socket->ids());
    }

    public function onError(string $on, SocketController $socket, Throwable $error)
    {
        $this->red('[error on ' . $on . ' - ' . $socket->id() . '] : ' . $error->getMessage())->br();
    }
}
