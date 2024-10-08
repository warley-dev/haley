<?php

// --------------------------------------------------------------------------|
//                            APP CONFIGURATIONS                             |
// --------------------------------------------------------------------------|

return [
    'lang' => 'en',
    'name' => env('APP_NAME', 'Haley'),
    'debug' => env('APP_DEBUG', false),

    // App helpers file
    'helpers' => [
        directoryRoot('app/Helpers/main.php')
    ],

    'session' => [
        'name' => 'HALEY',
        'regenerate' => false,
        'secure' => true,
        'files' => directoryRoot('storage/sessions') // null for default
    ],

    'ini' => [
        'timezone' => env('APP_TIMEZONE', 'America/Los_Angeles'),
        'max_execution_time' => 300,
        'max_input_time' => 0,
        'max_input_vars' => 300,

        'session.name' => 'haley',
        'session.cookie_secure' => true,
        'session.gc_maxlifetime' => 86400,
        'session.cookie_lifetime' => 86400,
        'session.cache_expire' => 86400
    ]
];
