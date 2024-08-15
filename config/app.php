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
        'lifetime' => 86400, // 1 day
        'files' => directoryRoot('storage/sessions') // null for default
    ],

    'ini' => [
        // timezone
        'timezone' => env('APP_TIMEZONE', 'America/Los_Angeles'),

        // This sets the maximum time in seconds a script is allowed to run before it is terminated by the parser.
        'max_execution_time' => 300,

        // This sets the maximum time in seconds a script is allowed to parse input data, like POST and GET. Set to 0 to allow unlimited time.
        'max_input_time' => 0,

        // How many input variables may be accepted (limit is applied to $_GET, $_POST and $_COOKIE superglobal separately).
        'max_input_vars' => 300,

        // session.gc_maxlifetime
        // session.cookie_lifetime
        // session.cookie_secure
        // session.cache_expire
        // session.name
    ]
];
