<?php

use Haley\Jobs\Job;

// --------------------------------------------------------------------------|
//                               JOB ROUTES                                  |
// --------------------------------------------------------------------------|

Job::namespace('App\Jobs')->name('test')->group(function () {
    Job::everyMinute(1, 'Test::one')->description('every 1 minute');
    Job::everyMinute(6, 'Um::one')->description('every 6 minute');

  
    Job::date('00:05 08/06/2023')->name('date')->description('especific date');
});
