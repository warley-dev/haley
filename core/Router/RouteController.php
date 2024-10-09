<?php

namespace Haley\Router;

use Haley\Collections\Config;
use Haley\Kernel;

class RouteController
{
    private array $configs = [];
    private array $route = [];

    private array  $names = [];

    private array|null $current = null;
    private array|null $params = null;
    private bool $method_valid = false;

    public function load()
    {
        $this->configs = Config::route('http', []);

        foreach ($this->configs as $name => $config) {

            Route::config($name);

            require_once $config['path'];

            Route::finish();
        }

        $this->route = Kernel::getMemory('route');

        $url = trim(filter_var(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), FILTER_SANITIZE_URL), '/');
        $method = request()->method();
        $domain = request()->domain();

        foreach ($this->route['routes'] as $key => $route) {
            if (!empty($this->configs[$route['config']]['middleware'])) {
                array_unshift($this->route['routes'][$key]['middleware'], $this->configs[$route['config']]['middleware']);
            }

            if (!empty($this->configs[$route['config']]['prefix'])) {
                $this->route['routes'][$key]['route'] = trim($this->configs[$route['config']]['prefix'] . '/' . $this->route['routes'][$key]['route'], '/');
            }

            if (!empty($route['name'])) $this->names[$route['name']] = trim(preg_replace('/{(.*?)}/', '{?}', $route['route']), '/');

            $params = $this->params($this->route['routes'][$key]['route'], $url);

            if ($params === false) continue;

            $this->params = $params;

            if (in_array($method, $route['methods'])) {
                $this->method_valid = true;

                if (count($route['domain'])) {
                    if (in_array($domain, $route['domain'])) {
                        $this->current = $this->route['routes'][$key];
                    }
                } else {
                    $this->current = $this->route['routes'][$key];
                }
            } else if ($this->current === null) {
                $this->current = $this->route['routes'][$key];
                $this->method_valid = false;
            }
        }

        // define('ROUTER_PARAMS', $this->params);
        // define('ROUTER_NAMES', $this->names);
        // define('ROUTER_NOW', $route_valid);

        Kernel::setMemory('route', $this->route);
        Kernel::setMemory('route.current', $this->current);
        Kernel::setMemory('route.params', $this->params);
        Kernel::setMemory('route.names', $this->names);
    }

    public function execute()
    {
        if ($this->current and $this->method_valid) {
            foreach ($this->current['middleware'] as $middleware) {
                if (is_string($middleware)) $middleware = '\App\Middlewares\\' . $middleware;

                executeCallable($middleware);
            }

            // if (!empty($this->current['config']['csrf'])) {
            //     if (!csrf()->check() and $this->current['config']['csrf']['active'] === true and !in_array('GET', $this->current['methods'])) return response()->abort(401);
            // }

            if ($this->current['type'] == 'url' and !empty($_GET)) {
                return response()->abort(405);
            }

            if ($this->current['type'] == 'redirect') {
                return redirect($this->current['action']['destination'], $this->current['action']['status']);
            }

            if ($this->current['type'] == 'view') {
                return view($this->current['action'][0], $this->current['action'][1]);
            }

            $result = executeCallable($this->current['action'], $this->params, $this->current['namespace']);

            if (is_string($result) || is_numeric($result)) {
                echo $result;
            } else if (is_array($result) || is_object($result)) {
                if (!is_callable($result)) return response()->json($result);
            }

            Kernel::terminate();
        } else if ($this->current and !$this->method_valid) {
            return response()->abort(405);
        } else {
            return response()->abort(404);
        }
    }

    private function params(string $route, string $url)
    {
        $route = trim($route, '/');
        $check = $route;
        $params = [];

        if (preg_match('/{(.*?)}/', $route)) {
            $array_route = explode('/', $route);
            $array_url = explode('/', $url);

            foreach ($array_route as $key => $value) {
                if (preg_match('/{(.*?)}/', $value, $math)) {
                    $param = str_replace(['?}', '{', '}'], '', $math[0]);

                    if (isset($array_url[$key])) {
                        $params[$param] = $array_url[$key];
                        $check = str_replace($math[0], $array_url[$key], $check);
                    } elseif (substr($value, -2) == '?}') {
                        $params[$param] = null;
                        $check = str_replace("/$math[0]", '', $check);
                    }
                }
            }
        }

        if ($check == $url) return $params;

        return false;
    }
}
