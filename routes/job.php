<?php

use Haley\Collections\Log;
use Haley\Job\Job;

// --------------------------------------------------------------------------|
//                               JOB ROUTES                                  |
// --------------------------------------------------------------------------|

Job::namespace('App\Jobs')->timeout(1)->unique()->group(function () {
    Job::everyDayAt(0, function () {
        Log::clean(['framework']);
    })->description('clean logs')->name('clean.logs')->unique();
});
