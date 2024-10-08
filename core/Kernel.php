<?php

namespace Haley;

use Haley\Collections\Config;
use Haley\Console\Console;
use Haley\Exceptions\Exceptions;
use Haley\Router\Route;
use Haley\Router\RouteMemory;

class Kernel
{
    /**
     * Type of execution http or console
     */
    static public string|null $type = null;

    /**
     * Framework memories
     */
    public static array $memories = [];

    /**
     * Callbacks to be executed at the end of the script
     */
    static private array $terminators = [];

    /**
     * Start framework
     */
    static public function run()
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

        return new self;
    }

    /**
     * Execute http
     */
    static public function http()
    {
        self::$type = 'http';

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

    /**
     * Execute console
     */
    static public function console()
    {
        self::$type = 'console';

        (new Exceptions)->handler(function () {
            // load helpers
            foreach (Config::app('helpers', []) as $helper) require_once $helper;

            // load console commands
            foreach (Config::route('console', []) as $console) require_once $console;

            Console::end();
        });
    }

    /**
     * Add callback when script ends
     */
    static public function onTerminate(string|array|callable $callback)
    {
        self::$terminators[] = $callback;
    }

    /**
     * Finish execution
     */
    static public function terminate()
    {
        foreach (self::$terminators as $callback) executeCallable($callback);

        if (!defined('HALEY_STOP')) define('HALEY_STOP', microtime(true));

        while (ob_get_level() > 0) ob_end_flush();

        exit;
    }

    /**
     * Set memory
     *
     * @return mixed
     */
    static public function setMemory(string|array $keys, mixed $value)
    {
        if (!is_array($keys)) $keys = explode('.', $keys);

        $current = &self::$memories;

        foreach ($keys as $key) {
            if (!array_key_exists($key, $current)) $current[$key] = [];

            $current = &$current[$key];
        }

        $current = $value;

        return $value;
    }

    /**
     * Get memory
     *
     * @return mixed|null
     */
    static public function getMemory(string|array $keys, mixed $default = null)
    {
        if (!is_array($keys)) $keys = explode('.', $keys);

        if (!array_key_exists($keys[0], self::$memories)) return $default;

        $value = self::$memories[$keys[0]];

        if (count($keys) === 1) return $value;

        unset($keys[0]);

        foreach ($keys as $key) {
            if (!array_key_exists($key, $value)) return $default;

            $value = $value[$key];
        };

        return $value;
    }

    /**
     * Remove from memory
     *
     * @return bool
     */
    static public function unsetMemory(string|array $keys)
    {
        if (!is_array($keys)) $keys = explode('.', $keys);

        $memories = &self::$memories;
        $lastKey = array_pop($keys);

        foreach ($keys as $key) {
            if (!isset($memories[$key]) || !is_array($memories[$key])) return false;

            $memories = &$memories[$key];
        }

        if (array_key_exists($lastKey, $memories)) {
            unset($memories[$lastKey]);

            return true;
        }

        return false;
    }
}
