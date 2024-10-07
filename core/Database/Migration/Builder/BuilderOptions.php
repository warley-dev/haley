<?php

namespace Haley\Database\Migration\Builder;

class BuilderOptions
{
    public function outoIncrement()
    {
        $key = array_key_last(BuilderMemory::$columns);

        if (in_array(BuilderMemory::$config['driver'], ['mysql', 'pgsql', 'mariadb'])) {
            BuilderMemory::$columns[$key]['options']['AUTOINCREMENT'] = true;
        }

        return $this;
    }

    public function primaryKey()
    {
        $key = array_key_last(BuilderMemory::$columns);

        if (in_array(BuilderMemory::$config['driver'], ['mysql', 'pgsql', 'mariadb'])) {
            BuilderMemory::$columns[$key]['options']['PRIMARY'] = true;
        }

        return $this;
    }

    public function comment(string $value)
    {
        $key = array_key_last(BuilderMemory::$columns);

        if (in_array(BuilderMemory::$config['driver'], ['mysql', 'pgsql', 'mariadb'])) {
            BuilderMemory::$columns[$key]['options']['COMMENT'] = $value;
        }

        return $this;
    }

    public function nullable(bool $value = true)
    {
        $key = array_key_last(BuilderMemory::$columns);

        if (in_array(BuilderMemory::$config['driver'], ['mysql', 'mariadb'])) {
            BuilderMemory::$columns[$key]['options']['NULLABLE'] = $value;
        } else if (BuilderMemory::$config['driver'] == 'pgsql') {
            BuilderMemory::$columns[$key]['options']['NULLABLE'] = $value;
        }

        return $this;
    }

    public function default(string $value, bool $raw = false)
    {
        $key = array_key_last(BuilderMemory::$columns);

        if (in_array(BuilderMemory::$config['driver'], ['mysql', 'pgsql', 'mariadb'])) {
            if (!$raw) $value = "'$value'";

            BuilderMemory::$columns[$key]['options']['DEFAULT'] = $value;
        }

        return $this;
    }

    public function onUpdate(string $value, bool $raw = false)
    {
        $key = array_key_last(BuilderMemory::$columns);

        if (in_array(BuilderMemory::$config['driver'], ['mysql', 'mariadb'])) {
            if (!$raw) $value = "'$value'";

            BuilderMemory::$columns[$key]['options']['ONUPDATE'] = $value;
        }

        return $this;
    }

    public function unique(string $name = null)
    {
        $key = array_key_last(BuilderMemory::$columns);

        if (in_array(BuilderMemory::$config['driver'], ['mysql', 'pgsql', 'mariadb'])) {
            $column = BuilderMemory::$columns[$key]['name'];

            if ($name === null) $name = 'unique_' . $column;

            BuilderMemory::addConstraint($name, 'UNIQUE', sprintf('(%s)', $this->quotes($column)));
        }

        return $this;
    }

    /**
     * BTREE | FULLTEXT | HASH
     */
    public function index(string|null $name = null, string $type = 'BTREE')
    {
        $key = array_key_last(BuilderMemory::$columns);
        $column = BuilderMemory::$columns[$key]['name'];

        if ($name === null) $name = 'index_' . $column;

        if (in_array(BuilderMemory::$config['driver'], ['mysql', 'pgsql', 'mariadb'])) BuilderMemory::$index[$name] = [
            'name' => $name,
            'type' => $type,
            'column' => $column
        ];

        return $this;
    }

    /**
     * Valid only when editing table
     */
    public function first()
    {
        $key = array_key_last(BuilderMemory::$columns);

        if (in_array(BuilderMemory::$config['driver'], ['mysql', 'mariadb'])) {
            BuilderMemory::$columns[$key]['options']['POSITION'] = 'FIRST';
        }

        return $this;
    }

    /**
     * Valid only when editing table
     */
    public function after(string $column)
    {
        $key = array_key_last(BuilderMemory::$columns);

        if (in_array(BuilderMemory::$config['driver'], ['mysql', 'mariadb'])) {
            BuilderMemory::$columns[$key]['options']['POSITION'] = sprintf('AFTER %s', $this->quotes($column));
        }

        return $this;
    }

    private function quotes(string $string)
    {
        $string = preg_replace('/\b(?!as\b)(\w+)\b/i', BuilderMemory::$config['quotes'] . '$1' . BuilderMemory::$config['quotes'], $string);
        $string = preg_replace('/(' . preg_quote(BuilderMemory::$config['quotes']) . ')\s/', '$1 ', $string);

        return $string;
    }
}
