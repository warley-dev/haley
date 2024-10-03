<?php

namespace Haley\Database\Migration\Builder;

class BuilderOptions
{
    public function comment(string $value)
    {
        $key = array_key_last(BuilderMemory::$columns);

        if (in_array(BuilderMemory::$config['driver'], ['mysql', 'pgsql', 'mariadb'])) {
            BuilderMemory::$columns[$key]['options']['COMMENT'] = "COMMENT '{$value}'";
        }

        return $this;
    }

    public function nullable(bool $value = true)
    {
        $key = array_key_last(BuilderMemory::$columns);

        if (in_array(BuilderMemory::$config['driver'], ['mysql', 'pgsql', 'mariadb'])) {
            BuilderMemory::$columns[$key]['options']['NULLABLE'] = $value ? 'NULL' : 'NOT NULL';
        }

        return $this;
    }

    public function default(string $value, bool $raw = false)
    {
        $key = array_key_last(BuilderMemory::$columns);

        if (in_array(BuilderMemory::$config['driver'], ['mysql', 'pgsql', 'mariadb'])) {
            if (!$raw) $value = "'$value'";

            BuilderMemory::$columns[$key]['options']['DEFAULT'] = 'DEFAULT ' . $value;
        }

        return $this;
    }

    public function onUpdate(string $value, bool $raw = false)
    {
        $key = array_key_last(BuilderMemory::$columns);

        if (in_array(BuilderMemory::$config['driver'], ['mysql', 'pgsql', 'mariadb'])) {
            if (!$raw) $value = "'$value'";

            BuilderMemory::$columns[$key]['options']['ONUPDATE'] = 'ON UPDATE ' . $value;
        }

        return $this;
    }

    public function unique(string $name = null)
    {
        $key = array_key_last(BuilderMemory::$columns);

        if (in_array(BuilderMemory::$config['driver'], ['mysql', 'pgsql', 'mariadb'])) {
            $column = BuilderMemory::$columns[$key]['name'];

            if ($name === null) $name = 'unique_' . BuilderMemory::$table . '_' . $column;

            BuilderMemory::addConstraint($name, 'UNIQUE', "(`$column`)");
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
        $table = BuilderMemory::$table;

        if ($name === null) $name = 'idx_' . $table . '_' . $column;

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

        if (in_array(BuilderMemory::$config['driver'], ['mysql', 'pgsql', 'mariadb'])) {
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

        if (in_array(BuilderMemory::$config['driver'], ['mysql', 'pgsql', 'mariadb'])) {
            BuilderMemory::$columns[$key]['options']['POSITION'] = "AFTER `{$column}`";
        }

        return $this;
    }
}
