<?php

namespace App\Controllers\Server;

use Haley\Server\WebSocket\WebSocket;
use Throwable;

class Teste
{
    protected array $clients = [];

    public function onOpen(int $id, array $params, array $header, WebSocket $ws)
    {
        $ws->send($ws->clients(), json_encode([
            'nome' => null,
            'mensagem' => null,
            'usuarios' => $ws->connections()
        ]));
    }

    public function onMessage(int $id, string $data, WebSocket $ws)
    {
        $data = json_decode($data, true);

        if (empty($data) or !array_key_exists('nome', $data)) return;

        if ($data['mensagem'] == 'close') {
            $ws->close($id);

            return;
        }

        if (!array_key_exists($id, $this->clients)) {
            $this->clients[$id] = $data['nome'];

            $ws->send($ws->clients(false), json_encode([
                'nome' => 'System',
                'mensagem' => $data['nome'] . ' entrou na sala',
                'usuarios' => $ws->connections()
            ]));
        }

        $ws->send($ws->clients(), json_encode([
            'nome' => $this->clients[$id],
            'mensagem' => $data['mensagem'],
            'usuarios' => $ws->connections()
        ]));
    }

    public function onClose(int $id, WebSocket $ws)
    {
        $clientes = $ws->clients(false);

        if (array_key_exists($id, $this->clients)) {
            $ws->send($clientes, json_encode([
                'nome' => 'System',
                'mensagem' => $this->clients[$id] . ' saiu da sala',
                'usuarios' => $ws->connections()
            ]));

            unset($this->clients[$id]);
        } else {
            $ws->send($clientes, json_encode([
                'nome' => null,
                'mensagem' => null,
                'usuarios' => $ws->connections()
            ]));
        }
    }

    public function onError(string $on, Throwable $error, WebSocket $ws)
    {
        dd($on, $error->getMessage());
    }
}
