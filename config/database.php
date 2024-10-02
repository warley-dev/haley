<?php

// --------------------------------------------------------------------------|
//                           DATABASE CONFIGURATIONS                         |
// --------------------------------------------------------------------------|

return [
    'default' => env('DB_CONNECTION', 'mysql'),

    'connections' => [
        'mysql' => [
            'driver' => env('DB_DRIVE', 'mysql'), // mysql, pgsql, mariadb
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', 3306),
            'database' => env('DB_DATABASE'),
            'username' => env('DB_USERNAME', ''),
            'password' => env('DB_PASSWORD', ''),
            'engine' => 'InnoDB',
            'charset' => 'utf8mb4',
            'collate' => 'utf8mb4_general_ci',
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, // PDO::FETCH_ASSOC -- array
                PDO::ATTR_PERSISTENT => true
            ]) : [],
        ]
    ],
];
