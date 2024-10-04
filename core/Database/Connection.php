<?php

namespace Haley\Database;

use ErrorException;
use Haley\Collections\Config;
use InvalidArgumentException;
use PDO;
use PDOException;

/**
 * Gerencia as conexões com o banco de dados
 */
class Connection
{
    public static array $drivers = ['mysql', 'pgsql', 'mariadb'];
    private static array $instances;

    /**
     * Criar conexão com o banco de dados
     * @return PDO
     */
    public static function instance(string|null $connection = null)
    {
        if (isset(self::$instances[$connection])) return self::$instances[$connection];

        $config = self::config($connection);

        $drive = $config['driver'];
        $host = $config['host'];
        $port = $config['port'];
        $dbname = $config['database'];
        $username = $config['username'];
        $password = $config['password'];
        $options = null;

        if (array_key_exists('options', $config) and is_array($config['options'])) $options = $config['options'];

        self::$instances[$connection] = new PDO("$drive:host=$host;port=$port;dbname=$dbname", $username, $password, $options);

        return self::$instances[$connection];
    }

    /**
     * @return array
     */
    public static function config(string|null $connection = null)
    {
        if ($connection === null) $connection = Config::database('default', null);
        if ($connection === null) throw new ErrorException('default connection not found');

        $config = Config::database('connections.' . $connection, []);

        $requireds = ['driver', 'host', 'port', 'database', 'username', 'password'];

        foreach ($requireds as $required) if (!array_key_exists($required, $config)) {
            throw new InvalidArgumentException(sprintf('%s connection required', $required));
        }

        if (!in_array($config['driver'], self::$drivers)) throw new ErrorException('unsupported database connection driver');

        $config['quotes'] = $config['driver'] == 'pgsql' ? '"' : '`';

        return $config;
    }

    public static function close(string $connection = null)
    {
        if ($connection !== null) {
            if (array_key_exists($connection, self::$instances)) {
                self::$instances[$connection] = null;
                unset(self::$instances[$connection]);
            }

            return;
        }

        foreach (self::$instances as $key => $instance) {
            self::$instances[$key] = null;
            unset(self::$instances[$key]);
        }

        self::$instances = [];
    }

    public function __destruct()
    {
        self::close();
    }
}
