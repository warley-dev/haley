<?php

// --------------------------------------------------------------------------|
//                           DATABASE CONFIGURATIONS                         |
// --------------------------------------------------------------------------|

return [
    'default' => env('DB_CONNECTION', 'mysql'),

    'connections' => [
        'mysql' => [
            'driver' => env('DB_DRIVE', 'mysql'),
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', 3306),
            'database' => env('DB_DATABASE'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),

            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, // PDO::FETCH_ASSOC | FETCH_OBJ
                PDO::ATTR_PERSISTENT => true
            ]) : []
        ],

        'pgsql' => [
            'driver' => 'pgsql',
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', 5432),
            'database' => env('DB_DATABASE'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'search_path' => 'public',

            'options' => array_filter([
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, // PDO::FETCH_ASSOC | FETCH_OBJ
                PDO::ATTR_PERSISTENT => true
            ])
        ],

        'mariadb' => [
            'driver' => 'mariadb',
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', 3306),
            'database' => env('DB_DATABASE'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD')
        ]
    ]
];
