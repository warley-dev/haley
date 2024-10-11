<?php

namespace Haley\Http;

use Haley\Kernel;

class Csrf
{
    public static function unset()
    {
        request()->session()->unset('csrf');
    }

    public static function token()
    {
        $session = request()->session('csrf');

        if (!empty($session['lifetime']) and $session['lifetime'] > date('dmYHis')) return $session['token'];

        $token = md5(bin2hex(random_bytes(10)));
        $config = Kernel::getMemory('route.config');
        $lifetime = 1800;

        if ($config) $lifetime = $config['csrf']['lifetime'] ?? 1800;

        request()->session()->set('csrf', [
            'token' => $token,
            'lifetime' => date('dmYHis', strtotime('+' . $lifetime . ' seconds'))
        ]);

        return $token;
    }

    public static function check()
    {
        $token = self::token();
        $header = request()->headers('X-CSRF-TOKEN') ?? request()->headers('X-Csrf-Token');
        $input = request()->post('_token');

        if ($token == $header or $token == $input) return true;

        return false;
    }
}
