<?php

use Haley\Jobs\Job;

// --------------------------------------------------------------------------|
//                               JOB ROUTES                                  |
// --------------------------------------------------------------------------|

Job::namespace('App\Jobs')->name('test')->group(function () {
    Job::everyMinute(1, 'Test::one')->description('every 1 minute');
    Job::everyMinute(6, 'Um::one')->description('every 6 minute');

    Job::everyHour(3, 'Test::one')->description('every hours');

    Job::everyDayAt('01:22')->description('every dat at');

    Job::thursdaysAt('02:18')->description('Quinta-feira');

    Job::raw([02, 03], date('H'))->description('raw');

    Job::dayAt(8, '02:48')->description('day at');

    Job::date('00:05 08/06/2023')->name('date')->description('especific date');
});
