<?php

namespace App\Controllers\WebSocket;

use Haley\Collections\Log;
use Haley\Console\Lines;
use Haley\WebSocket\InterfaceSocketClient;
use Haley\WebSocket\SocketClient;

use Throwable;

class Chat extends Lines implements InterfaceSocketClient
{
    private array $history = [];
    private array $users = [];

    public function onOpen(SocketClient $client)
    {
        $client->send(json_encode([
            'open' => 'Bem vindo ao chat'
        ]), $client->id());

        $client->send(json_encode([
            'online' => $client->count()
        ]));

        var_dump($client->header($client->id(), 'Host'));

        if (count($this->history)) foreach ($this->history as $message) {
            $client->send($message, $client->id());
        }
    }

    public function onMessage(string $message, int $message_id, SocketClient $client)
    {
        $message = json_decode($message, true);

        if ($message && $client->id()) {
            if ($message['message'] == 'close') {
                $client->close($client->id());

                return;
            }

            if ($message['login']) {
                if (!in_array($client->id(), $this->users)) {
                    $client->setProps($client->id(), [
                        'user' => $message['user']
                    ]);

                    $this->users[] = $client->id();
                }

                $client->send(json_encode([
                    'user' => 'CHAT',
                    'message' => $message['user'] . ' entrou!',
                ]), $client->ids());

                $message['login'] = false;
            }
        }

        $this->history[$message_id] = json_encode($message);

        $client->send(json_encode($message), $client->ids());
    }

    public function onClose(SocketClient $client)
    {
        if ($client->id()) {
            $props = $client->getProps($client->id());

            if ($props) {
                $message = $props['user'] . ' desconectou!';

                $client->send(json_encode([
                    'disconnect' => $message,
                ]), $client->ids());
            }
        }

        $client->send(json_encode([
            'online' => $client->count()
        ]), $client->ids());
    }

    public function onError(string $on, SocketClient $client, Throwable $error)
    {
        $this->red('[error on ' . $on . ' - ' . $client->id() . '] : ' . $error->getMessage() . ' - ' . $error->getFile()  . ' ' . $error->getLine())->br();

        Log::create('websocket', $error->getMessage());
    }
}
