<?php
namespace App\Jobs;

use Haley\Collections\Log;

class Um
{
    public function one()
    {
        Log::create('tests','job');    
        sleep(5);  
        dd('executado');
    }
}
