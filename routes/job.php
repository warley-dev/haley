<?php

use Haley\Jobs\Job;

// --------------------------------------------------------------------------|
//                               JOB ROUTES                                  |
// --------------------------------------------------------------------------|

Job::namespace('App\Jobs')->name('test')->group(function () {
    Job::minute(1, 'Test::one')->name('minute');
    Job::minute(2, 'Um::one')->name('minute');
});
