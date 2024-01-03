<?php
// --------------------------------------------------------------------------|
//                            ROUTES CONFIGURATIONS                          |
// --------------------------------------------------------------------------|

return [
    'http' => [
        'web' => [
            'path' => directoryRoot('routes/web.php'),
            'middleware' => 'Routes::web',
            'prefix' => null,

            'csrf' => [
                'active' => true,
                'lifetime' => 1800 // 30 minutes
            ]
        ],

        'api' => [
            'path' => directoryRoot('routes/api.php'),
            'middleware' => 'Routes::api',
            'prefix' => 'api',

            'csrf' => [
                'active' => false,
                'lifetime' => 1800 // 30 minutes
            ]
        ]
    ],

    'console' => [
        directoryRoot('routes/console.php')
    ],

    'job' =>  [
        directoryRoot('routes/job.php')
    ]
];
