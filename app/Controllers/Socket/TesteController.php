<?php

namespace App\Controllers\Socket;

use Haley\Collections\Log;
use Haley\Console\Lines;
use Haley\WebSocket\SocketController;
use Throwable;

class TesteController extends Lines
{
    private array $history = [];
    private array $users = [];

    public function onOpen(SocketController $socket)
    {
        $socket->send([
            'open' => 'Bem vindo ao chat'
        ], $socket->id());

        var_dump('open');

        $socket->send([
            'online' => $socket->count()
        ]);

        if (count($this->history)) foreach ($this->history as $message) {
            $socket->send($message, $socket->id());
        }
    }

    public function onMessage(mixed $message, int $id, SocketController $socket)
    {
        $message = json_decode($message, true);

        if ($message && $socket->id()) {
            if ($message['message'] == 'close') {
                $socket->close($socket->id());

                return;
            }

            if ($message['login']) {
                if (!in_array($socket->id(), $this->users)) {
                    $socket->setProps($socket->id(), [
                        'user' => $message['user']
                    ]);

                    $this->users[] = $socket->id();
                }

                $socket->send([
                    'user' => 'CHAT',
                    'message' => $message['user'] . ' entrou!',
                ], $socket->ids());

                $message['login'] = false;
            }
        }

        $this->history[$id] = $message;

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

        Log::create('websocket', $error->getMessage());
    }
}
