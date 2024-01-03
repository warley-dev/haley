<?php

namespace App\Controllers\Socket;

class TesteController
{
    public function onOpen()
    {
        return 'ola';
    }

    public function onMessage()
    {
      
    }

    public function onClose()
    {

    }

    public function onError()
    {

    }
}
