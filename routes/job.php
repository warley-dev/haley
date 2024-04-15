<?php

use Haley\Collections\Log;
use Haley\Jobs\Job;

// --------------------------------------------------------------------------|
//                               JOB ROUTES                                  |
// --------------------------------------------------------------------------|

Job::namespace('App\Jobs')->name('test')->timeout(5)->unique()->group(function () {

    Job::everyMinute(1, function () {  
        Log::clean(['jobs', 'database', 'connection', 'migration','websocket']);
    })->description('Clean logs')->name('test')->unique();


    // Job::everyMinute(1, function () {
    //     sleep(80);
    //     $y = date('dfdfgdfgg');
    // })->description('every 1 minute')->timeout(1)->unique(true);

    // Job::everyMinute(6, 'Um::one')->description('every 6 minute')->name('aaaaaaaa');



    Job::date('00:05 08/06/2023')->name('date')->description('especific date')->unique();
});