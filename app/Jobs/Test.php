<?php
namespace App\Jobs;

use Haley\Collections\Log;

class Test
{
    public function one()
    {
        Log::create('tests','job');

        dd('executado');
    }
}
