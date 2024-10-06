<?php

namespace Haley\Database\Query;

use Haley\Database\Connection;
use Haley\Database\Query\Scheme\Column;
use Haley\Database\Query\Scheme\Constraint;
use Haley\Database\Query\Scheme\Table;

class Scheme
{
    private array $config = [];

    public function __construct(string|null $connection = null)
    {
        $this->connection($connection);

        return $this;
    }

    public function connection(string|null $connection = null)
    {
        $this->config = Connection::config($connection);

        return $this;
    }

    public function table()
    {
        return new Table($this->config);
    }

    public function column()
    {
        return new Column($this->config);
    }

    public function constraint()
    {
        return new Constraint($this->config);
    }
}
