<?php

use Haley\Collections\Memory;
use Haley\Collections\Password;
use Haley\Env\Env;
use Haley\Exceptions\Debug;
use Haley\Http\Csrf;
use Haley\Http\Redirect;
use Haley\Http\Request;
use Haley\Http\Response;
use Haley\Http\Route;
use Haley\Validator\ValidatorHelper;
use Haley\View\View;

if (!function_exists('view')) {
    /**
     * @param string $view
     * @param array|object $params
     * @return Template
     */
    function view(string $view, array|object $params = [], bool $render = true, string|null $path = null)
    {
        return (new View)->view($view, $params, $render, $path);
    }
}

if (!function_exists('isJson')) {
    function isJson($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}

if (!function_exists('redirect')) {
    /**
     * Redirecionamentos
     */
    function redirect(string|null $destination = null, $status = 302)
    {
        if ($destination !== null) {
            return (new Redirect)->destination($destination, $status);
        }

        return new Redirect;
    }
}

if (!function_exists('request')) {
    /**
     * Funções request
     */
    function request()
    {
        return new Request;
    }
}

if (!function_exists('response')) {
    /**
     * Funções response
     */
    function response()
    {
        return new Response;
    }
}

if (!function_exists('abort')) {
    function abort(int $status, string|null $mesage = null)
    {
        return (new Response)->abort($status, $mesage);
    }
}

if (!function_exists('validator')) {
    /**
     * Validator helper view
     * @return string|array|false|ValidatorHelper
     */
    function validator(string $input = null)
    {
        if ($input != null) {
            return (new ValidatorHelper)->first($input);
        }

        return (new ValidatorHelper);
    }
}

if (!function_exists('env')) {
    /**
     * Retorna o valor declarado em .env
     * @return mixed
     */
    function env(string $key = null, mixed $or = null)
    {
        return Env::env($key, $or);
    }
}

if (!function_exists('password')) {
    function password()
    {
        return new Password;
    }
}

if (!function_exists('route')) {
    /**
     * Retorna a URL da rota nomeada.
     * @return Haley\Http\Route|string|null
     */
    function route(string|null $name = null, string|array|null ...$params)
    {
        if (!empty($params[0])) if (is_array($params[0])) $params = $params[0];

        if ($name !== null) return Route::name($name, $params);

        return new Route;
    }
}

if (!function_exists('old')) {
    /**
     * Retorna o valor de uma request antiga
     * @return mixed
     */
    function old(string $input = null)
    {
        $page = request()->url();
        $session = request()->session('FRAMEWORK');

        if (!empty($session['old'][$page][$input])) return $session['old'][$page][$input];

        return null;
    }
}

if (!function_exists('csrf')) {
    function csrf()
    {
        return new Csrf;
    }
}

if (!function_exists('dd')) {
    /**
     * @return string
     */
    function dd()
    {
        $backtrace = debug_backtrace();
        $line = $backtrace[0]['line'] ?? '';
        $file = $backtrace[0]['file'] ?? '';

        if (Memory::get('kernel') == 'console') {
            foreach (func_get_args() as $arg) var_dump($arg);

            return;
        }

        return (new Debug)->dd($line, $file, func_get_args());
    }
}

if (!function_exists('formatSize')) {
    function formatSize(int $bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' gb';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' mb';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' kb';
        } elseif ($bytes) {
            $bytes = $bytes . ' b';
        } else {
            $bytes = '0 b';
        }

        return $bytes;
    }
}

if (!function_exists('middleware')) {
    function middleware(string|array $middlewares)
    {
        if (is_string($middlewares)) $middlewares = [$middlewares];

        if (is_array($middlewares) and count($middlewares) > 0) {
            foreach ($middlewares as $middleware) {
                if (str_contains($middleware, '::')) {
                    $params = explode('::', $middleware);
                } elseif (str_contains($middleware, '@')) {
                    $params = explode('@', $middleware);
                }

                $class = "\App\Middlewares\\{$params[0]}";
                $rum = new $class;
                $rum->{$params[1]}();

                if ($rum->response == false) return false;
            }
        }

        return true;
    }
}

if (!function_exists('directorySeparator')) {
    function directorySeparator(string $directory)
    {
        return str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $directory);
    }
}

if (!function_exists('directoryPrivate')) {
    function directoryPrivate(string|null $path = null)
    {
        if ($path === null) return DIRECTORY_PRIVATE;

        return DIRECTORY_PRIVATE . DIRECTORY_SEPARATOR . trim(directorySeparator($path), DIRECTORY_SEPARATOR);
    }
}

if (!function_exists('directoryPublic')) {
    function directoryPublic(string|null $path = null)
    {
        if ($path === null) return DIRECTORY_PUBLIC;

        return DIRECTORY_PUBLIC . DIRECTORY_SEPARATOR . trim(directorySeparator($path), DIRECTORY_SEPARATOR);
    }
}

if (!function_exists('directoryResources')) {
    function directoryResources(string|null $path = null)
    {
        if ($path === null) return DIRECTORY_RESOURCES;

        return DIRECTORY_RESOURCES . DIRECTORY_SEPARATOR . trim(directorySeparator($path), DIRECTORY_SEPARATOR);
    }
}

if (!function_exists('directoryRoot')) {
    function directoryRoot(string|null $path = null)
    {
        if ($path === null) return DIRECTORY_ROOT;

        return DIRECTORY_ROOT . DIRECTORY_SEPARATOR . trim(directorySeparator($path), DIRECTORY_SEPARATOR);
    }
}

if (!function_exists('directoryHaley')) {
    function directoryHaley(string|null $path = null)
    {
        if ($path === null) return DIRECTORY_HALEY;

        return DIRECTORY_HALEY . DIRECTORY_SEPARATOR . trim(directorySeparator($path), DIRECTORY_SEPARATOR);
    }
}

if (!function_exists('createDir')) {
    function createDir(string $path)
    {
        $path = dirname(directorySeparator($path));

        if (file_exists($path)) return true;

        return file_exists($path) ? true : mkdir($path, 0777, true);
    }
}

if (!function_exists('deleteDir')) {
    function deleteDir(string $path)
    {
        $path = dirname(directorySeparator($path));

        if (!file_exists($path)) return true;

        $directory_iterator = new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS);
        $iterator = new RecursiveIteratorIterator($directory_iterator, RecursiveIteratorIterator::CHILD_FIRST);

        foreach ($iterator as $file) $file->isFile() ? unlink($file->getPathname()) : rmdir($file->getPathname());

        return rmdir($path);
    }
}

if (!function_exists('deleteFile')) {
    function deleteFile(string $path)
    {
        $path = directorySeparator($path);

        return file_exists($path) ? unlink($path) : true;
    }
}

if (!function_exists('executeCallable')) {
    function executeCallable(string|array|callable $callable, array $args = [], string|null $namespace = null)
    {
        $callback = null;

        $args = array_values($args);

        $namespace = !empty($namespace) ? trim($namespace, '\\') . '\\' : '';

        if (is_string($callable)) {
            if (str_contains($callable, '::')) {
                $params = explode('::', $callable);
            } elseif (str_contains($callable, '@')) {
                $params = explode('@', $callable);
            }

            if (array_key_exists(0, $params) and array_key_exists(1, $params)) {
                $callable = [];
                $class = $namespace . $params[0];
                $callable[0] = new $class;

                $callable[1] = $params[1];
                $reflection = new ReflectionMethod($callable[0], $callable[1]);
                $callback = $callable;
            }
        } elseif (is_array($callable)) {
            if (count($callable) == 1) {
                return new $callable[0];
            } else {
                $callable[0] = new $callable[0];
                $reflection = new ReflectionMethod($callable[0], $callable[1]);
            }

            $callback = $callable;
        } elseif (is_callable($callable)) {
            $callback = $callable;
            $reflection = new ReflectionFunction($callback);
        }

        if (is_callable($callback)) {
            $parameters = $reflection->getParameters();
            $args_valid = [];

            foreach ($parameters as $key => $value) {
                $arg_name = $value->getName();

                if (array_key_exists($key, $args)) $args_valid[$arg_name] = $args[$key];
            }

            return call_user_func_array($callback, $args_valid);
        }

        return null;
    }
}

if (!function_exists('getMemoryUsage')) {
    function getMemoryUsage()
    {
        $memory = [];

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $output = shell_exec("wmic os get FreePhysicalMemory,TotalVisibleMemorySize /Format:List");

            if ($output) {
                $lines = explode("\n", $output);

                foreach ($lines as $line) {
                    if (strpos($line, "TotalVisibleMemorySize") !== false) $memory['total'] = trim(explode("=", $line)[1]);
                    if (strpos($line, "FreePhysicalMemory") !== false) $memory['free'] = trim(explode("=", $line)[1]);
                }

                $memory['total'] = round($memory['total'] / 1024, 2);
                $memory['free'] = round($memory['free'] / 1024, 2);
                $memory['used'] = round($memory['total'] - $memory['free'], 2);
            }
        } else {
            $output = shell_exec("cat /proc/meminfo");

            if ($output) {
                $lines = explode("\n", $output);

                foreach ($lines as $line) {
                    if (strpos($line, "MemTotal:") !== false) $memory['total'] = trim(explode(":", $line)[1]);
                    if (strpos($line, "MemFree:") !== false) $memory['free'] = trim(explode(":", $line)[1]);
                    if (strpos($line, "MemAvailable:") !== false) $memory['available'] = trim(explode(":", $line)[1]);
                }

                $memory['total'] = round(trim(str_replace('kB', '', $memory['total'])) / 1024, 2);
                $memory['free'] = round(trim(str_replace('kB', '', $memory['free'])) / 1024, 2);
                $memory['available'] = round(trim(str_replace('kB', '', $memory['available'])) / 1024, 2);
                $memory['used'] = round($memory['total'] - $memory['free'], 2);
            }
        }

        return $memory;
    }
}
