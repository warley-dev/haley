<?php

use Haley\Collections\Log;
use Haley\Jobs\Job;

// --------------------------------------------------------------------------|
//                               JOB ROUTES                                  |
// --------------------------------------------------------------------------|

Job::namespace('App\Jobs')->name('test')->group(function () {

    Job::everyHour(1, function () {
        Log::clean(['jobs', 'database', 'connection', 'migration']);
    })->description('Clean logs')->name('test');


    Job::everyMinute(1, 'Test::one')->description('every 1 minute');
    Job::everyMinute(6, 'Um::one')->description('every 6 minute');
    


    Job::date('00:05 08/06/2023')->name('date')->description('especific date');
});
