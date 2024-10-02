<?php

use Haley\Collections\Log;
use Haley\Jobs\Job;

// --------------------------------------------------------------------------|
//                               JOB ROUTES                                  |
// --------------------------------------------------------------------------|

// alterar modo de funcionamento para data/hora do php

Job::namespace('App\Jobs')->name('test')->timeout(5)->unique()->group(function () {
    Job::everyMinute(1, function () {
        dd('aa');

        sleep(5);
    })->description('Job example')->name('test')->unique();


    Job::everyMinute(1, function () {
        sleep(5);
        dd('aa');
    })->description('timeout 5 min')->timeout(5)->name('test_2')->unique();

    // Job::everyMinute(1, function () {
    //     Log::clean(['jobs', 'database', 'connection', 'migration','websocket']);
    // })->description('Clean logs')->name('test')->unique();

    // Job::everyMinute(1, function () {
    //     sleep(80);
    //     $y = date('dfdfgdfgg');
    // })->description('every 1 minute')->timeout(1)->unique(true);

    // Job::everyMinute(6, 'Um::one')->description('every 6 minute')->name('aaaaaaaa');

    // Job::date('01:20 05/05/2024')->name('date')->description('especific date')->unique();
});
