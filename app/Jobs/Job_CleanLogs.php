<?php
namespace App\Jobs;
use Haley\Cron;
use Haley\Collections\Log;

class Job_CleanLogs 
{
    /**
     * CUIDADO: Se o escript for muito demorado e recomendado que se crie outro documento cronjob para que seja executado de forma assíncrona.
     */
    public function job(Cron $schedule)
    {  
       
        $schedule->everyMinute(1,action: function(){
            $files = ['cronjob','database','migration'];

            foreach ($files as $file) {
                Log::clean($file);          
            } 
                       
        })->description('clean logs');
    }
}