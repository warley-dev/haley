<?php

use Haley\Kernel;

define('ROOT', dirname(__DIR__));

!file_exists(ROOT . '/vendor/autoload.php') ? die('Autoload not found') : require ROOT . '/vendor/autoload.php';

$kernel = new Kernel;

$kernel->run()->app();

$kernel->terminate();