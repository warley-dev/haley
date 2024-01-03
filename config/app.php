<?php
// --------------------------------------------------------------------------|
//                            APP CONFIGURATIONS                             |
// --------------------------------------------------------------------------|

return [
    'lang' => 'en',
    'name' => env('APP_NAME', 'Haley'),
    'debug' => env('APP_DEBUG', false),
    'timezone' => env('APP_TIMEZONE', 'America/Los_Angeles'),

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
    ]
];
