<?php

namespace Haley\Database\Query;

use Haley\Database\Connection;
use InvalidArgumentException;

class BuilderController
{
    // protected string $query;
    // protected array $bindparams = [];
    protected array $params = [];

    protected function add(string $action, mixed $params, bool $array = true)
    {
        if ($array == true) {
            $this->params[$action][] = $params;
        } else {
            $this->params[$action] = $params;
        }
    }

    protected function keyLast(string $action)
    {
        if (!array_key_exists($action, $this->params)) return null;

        return array_key_last($this->params[$action]);
    }

    protected function executeProcessor(string $command, string|null $connection = null)
    {
        $config = Connection::config($connection);

        $builder = new BuilderProcessor($config, $this->params);

        return $builder->query($command);
    }
}
