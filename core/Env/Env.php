<?php

namespace Haley\Env;

class Env
{
    /**
     * Env values
     */
    protected static $env = [];

    /**
     * Variáveis de ambiente env
     * @return string|array|int|false|true|null
     */
    public static function env(string $name, mixed $or = null)
    {
        if (!count(self::$env)) self::$env = self::envRead();

        if (array_key_exists($name, self::$env)) return self::$env[$name];

        return $or;
    }

    /**
     * @return array
     */
    private static function envRead()
    {
        $file = array_filter(file(directoryRoot('.env')));
        $env = [];

        foreach ($file as $line) {
            if ($line[0] === '#') continue;

            $line = trim($line);

            if (!strlen($line)) continue;

            $item = explode('=', $line, 2);

            $name = trim($item[0]);
            $value = array_key_exists(1, $item) ? $item[1] : null;

            if ($value !== null) $value = strlen($value) ? self::checkValue(trim($value)) : null;

            $env[$name] = $value;
        }

        foreach ($env as $name => $value) {
            if (!$value) continue;

            $padrao = '/\${(.*?)}/';

            if (preg_match($padrao, $value, $matches)) {
                if (array_key_exists($matches[1], $env)) {
                    $env[$name] = str_replace($matches[0], $env[$matches[1]], $value);
                }
            }
        }

        return $env;
    }

    /**
     * Check value
     */
    private static function checkValue($value)
    {
        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return null;
        }

        if (filter_var($value, FILTER_VALIDATE_INT) !== false) return (int)$value;
        if (filter_var($value, FILTER_VALIDATE_FLOAT) !== false) return (float)$value;

        return $value;
    }
}
