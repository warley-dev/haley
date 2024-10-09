<?php

namespace Haley\Router;

class Middleware
{
    public function abort(int $status = 403, string|null $mesage = null)
    {
        return response()->abort($status, $mesage);
    }

    public function redirect(string|null $destination = null, $status = 302)
    {
        return redirect($destination, $status);
    }

    public function headers(string $name = null)
    {
        return request()->headers($name);
    }

    public function session(string $key = null)
    {
        return request()->session($key);
    }

    public function request()
    {
        return request();
    }

    public function route(string|null $name = null, string|array|null ...$params)
    {
        return route($name, $params);
    }
}
