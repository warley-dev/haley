<?php

use Haley\Collections\Log;
use Haley\Job\Job;

// --------------------------------------------------------------------------|
//                               JOB ROUTES                                  |
// --------------------------------------------------------------------------|

Job::namespace('App\Jobs')->timeout(1)->unique()->group(function () {
    Job::dayAt(3, 0, function () {
        Log::clean(['jobs', 'database', 'connection', 'migration', 'websocket']);
    })->description('clean logs')->name('clean.logs')->unique();
});
