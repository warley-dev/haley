<?php

namespace Haley\Router;

class RouteResolve
{
    private string $url;
    private string $method;
    private string|null $domain;
    private array $params = [];
    private array $names = [];

    public function __construct()
    {
        $this->url = trim(filter_var(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), FILTER_SANITIZE_URL), '/');
        $this->method = request()->method();
        $this->domain = request()->domain();
    }

    public function read(array $routes)
    {
        $route_valid = null;

        foreach ($routes as $route) {
            if (!empty($route['name'])) {
                $this->names[$route['name']] = trim(preg_replace('/{(.*?)}/', '{?}', $route['route']), '/');
            }

            if (!$this->check($route['route'])) continue;

            if (in_array($this->method, $route['methods'])) {
                if (count($route['domain'])) {
                    if (in_array($this->domain, $route['domain'])) $route_valid = $route;
                } else {
                    $route_valid = $route;
                }
            } else {
                define('ROUTER_NOW', $route);
                return response()->abort(405);
            }
        }

        if (empty($route_valid)) return response()->abort(404);

        define('ROUTER_PARAMS', $this->params);
        define('ROUTER_NAMES', $this->names);
        define('ROUTER_NOW', $route_valid);

        return $this->request($route_valid);
    }

    private function check(string $route)
    {
        $route = trim($route, '/');
        $check = $route;
        $params = [];

        if (preg_match('/{(.*?)}/', $route)) {
            $array_route = explode('/', $route);
            $array_url = explode('/', $this->url);

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

        if ($check == $this->url) {
            $this->params = $params;

            return true;
        }

        return false;
    }

    public function request(false|array $route)
    {
        $this->old();

        if (!empty($route['config']['middleware'])) array_push($route['middleware'], $route['config']['middleware']);

        if (!empty($route['middleware'])) {
            $middlewares = [];

            foreach ($route['middleware'] as $middleware) {
                if (is_array($middleware)) {
                    foreach ($middleware as $value) {
                        $middlewares[] = $value;
                    }
                } else {
                    $middlewares[] = $middleware;
                }
            }

            if (!middleware($middlewares)) return response()->abort(403);
        }

        if (!empty($route['config']['csrf'])) {
            if (!csrf()->check() and $route['config']['csrf']['active'] === true and !in_array('GET', $route['methods'])) return response()->abort(401);
        }

        if ($route['type'] == 'url' and !empty($_GET)) {
            return response()->abort(405);
        }

        if ($route['type'] == 'redirect') {
            return redirect($route['params']['destination'], $route['params']['status']);
        }

        if ($route['type'] == 'view') {
            return view($route['params'][0], $route['params'][1]);
        }

        // return new RouteAction($route['params']);







        $namespace = null;
        $args = [];

        if (defined('ROUTER_NOW')) {
            if (!empty(ROUTER_NOW['namespace'])) $namespace = ROUTER_NOW['namespace'] . '\\';
        }

        if (defined('ROUTER_PARAMS')) {
            if (!empty(ROUTER_PARAMS)) $args = ROUTER_PARAMS;
        }

        return $this->result(executeCallable($route['params'], $args, $namespace));
    }

    private function result(mixed $value)
    {
        if (is_string($value) || is_numeric($value)) {
            echo $value;
        } else if (is_array($value) || is_object($value)) {
            if (!is_callable($value)) return response()->json($value);
        }
    }

    private function old()
    {
        request()->session()->replace('HALEY', ['old' => null]);

        if (isset($_SERVER['HTTP_REFERER'])) {
            $request = request()->all();

            if (!empty($request)) {
                $url = parse_url($_SERVER['HTTP_REFERER']);
                $page = request()->url($url['path'] ?? $url['host'] ?? null);
                request()->session()->replace('HALEY', ['old' => [$page => $request]]);
            }
        }

        return;
    }
}
