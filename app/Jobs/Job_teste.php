<?php
namespace App\Jobs;
use Haley\Cron;

class Job_teste 
{
    /**
     * CUIDADO: Se o escript for muito demorado e recomendado que se crie outro documento cronjob para que seja executado de forma assíncrona.
     */
    public function job(Cron $schedule)
    {
        $schedule->everyMinute(1,function(){

        })->description('teste');

        

        $schedule->cron('01:26','31/13/2022',function(){

        })->description('teste');

        // dd( date('l'));
        // Monday, Tuesday, Wednesday, Friday, Saturday, Sunday
    }
}