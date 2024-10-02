<?php

use Haley\Collections\Log;
use Haley\Job\Job;

// --------------------------------------------------------------------------|
//                               JOB ROUTES                                  |
// --------------------------------------------------------------------------|

// alterar modo de funcionamento para data/hora do php

Job::namespace('App\Jobs')->timeout(1)->unique()->group(function () {
    Job::everyMinute(1, function () {
        dd('aa');

        sleep(120);
    })->description('Job example')->name('test')->unique();


    Job::everyMinute(1, function () {
        sleep(5);
    })->description('test')->name('test_rename')->unique();

    Job::dayAt(2, 14, function () {
        Log::clean(['jobs', 'database', 'connection', 'migration', 'websocket']);
    })->description('Clean logs')->name('clean.logs')->unique();

    // Job::everyMinute(1, function () {
    //     sleep(80);
    //     $y = date('dfdfgdfgg');
    // })->description('every 1 minute')->timeout(1)->unique(true);

    // Job::everyMinute(6, 'Um::one')->description('every 6 minute')->name('aaaaaaaa');

    // Job::date('01:20 05/05/2024')->name('date')->description('especific date')->unique();
});
