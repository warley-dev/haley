<?php
namespace App\Jobs;
use Haley\Cron;

/**
 * CUIDADO: Se o escript for muito demorado e recomendado que se crie outro documento cronjob para que seja executado de forma assíncrona.
 */        
class teste 
{
    public function job(Cron $schedule)
    {
        $schedule->cron('03:03','03/03/2023',function(){

        })->description('example');
    }
}