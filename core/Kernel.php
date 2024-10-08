<?php

namespace Haley;

use Haley\Collections\Config;
use Haley\Collections\Memory;
use Haley\Console\Console;
use Haley\Exceptions\Exceptions;
use Haley\Router\Route;
use Haley\Router\RouteMemory;

class Kernel
{
    private array $terminators = [];

    public function run()
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_erros', 1);

        error_reporting(E_ALL);

        // define('DIRECTORY_HALEY', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'src');
        define('DIRECTORY_HALEY', DIRECTORY_ROOT . DIRECTORY_SEPARATOR . 'core');

        if (Config::app('ini.timezone')) date_default_timezone_set(Config::app('ini.timezone'));

        (new Exceptions)->handler(function () {
            foreach (Config::app('ini', []) as $option => $value) ini_set($option, $value);
        });

        return $this;
    }

    public function app()
    {
        Memory::set('kernel', 'app');

        (new Exceptions)->handler(function () {
            // session settings
            $session_files = Config::app('session.files', null);

            if ($session_files) createDir(Config::app('session.files'));

            session_save_path($session_files);

            if (!isset($_SESSION)) session_start();

            session_regenerate_id(Config::app('session.regenerate', false));

            if (!request()->session()->has('HALEY')) request()->session()->set('HALEY');

            // start ob
            ob_start();

            // load helpers
            foreach (Config::app('helpers', []) as $helper) require_once $helper;

            // load routers
            $routes = Config::route('http', []);

            if ($routes) foreach ($routes as $name => $config) {
                if (!file_exists($config['path'])) continue;

                $config['name'] = $name;
                RouteMemory::resetAttributes();
                RouteMemory::$config = $config;
                require_once $config['path'];
            }

            Route::end();
        });
    }

    public function console()
    {
        Memory::set('kernel', 'console');

        (new Exceptions)->handler(function () {
            // load helpers
            foreach (Config::app('helpers', []) as $helper) require_once $helper;

            // load console commands
            foreach (Config::route('console', []) as $console) require_once $console;

            Console::end();
        });
    }

    public function onTerminate(string|array|callable $callback)
    {
        $this->terminators[] = $callback;
    }

    public function terminate()
    {
        foreach ($this->terminators as $callback) executeCallable($callback);

        if (!defined('HALEY_STOP')) define('HALEY_STOP', microtime(true));

        while (ob_get_level() > 0) ob_end_flush();

        exit;
    }
}
