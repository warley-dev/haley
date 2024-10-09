<?php

namespace Haley;

use Haley\Collections\Config;
use Haley\Console\Console;
use Haley\Exceptions\Exceptions;
use Haley\Router\RouteController;

class Kernel
{
    /**
     * Type of execution http or console
     */
    public static string|null $type = null;

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
    public static function run()
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
    public static function http()
    {
        self::$type = 'http';

        (new Exceptions)->handler(function () {
            // start ob
            ob_start();

            // start session
            $session_path = Config::app(['ini', 'session.save_path'], null);
            if ($session_path) createDir($session_path);
            session_start();

            // load helpers
            foreach (Config::app('helpers', []) as $helper) require_once $helper;

            // load routers
            $route = new RouteController();
            $route->load();

            // execute route
            $route->execute();
        });
    }

    /**
     * Execute console
     */
    public static function console()
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
     * Set memory
     *
     * @return mixed
     */
    public static function setMemory(string|array $keys, mixed $value)
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
    public static function getMemory(string|array $keys, mixed $default = null)
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
    public static function unsetMemory(string|array $keys)
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

    /**
     * Add callback when script ends
     */
    public static function onTerminate(string|array|callable $callback)
    {
        self::$terminators[] = $callback;
    }

    /**
     * Finish execution
     */
    public static function terminate()
    {
        foreach (self::$terminators as $callback) executeCallable($callback);

        if (!defined('HALEY_STOP')) define('HALEY_STOP', microtime(true));

        while (ob_get_level() > 0) ob_end_flush();

        exit;
    }
}
