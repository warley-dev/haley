<?php

use Haley\Kernel;

define('DIRECTORY_ROOT', dirname(__DIR__));

!file_exists(DIRECTORY_ROOT . '/vendor/autoload.php') ? die('Autoload not found') : require DIRECTORY_ROOT . '/vendor/autoload.php';

$kernel = new Kernel;

$kernel->run()->app();

$kernel->terminate();