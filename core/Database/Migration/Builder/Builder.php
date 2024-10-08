<?php

namespace Haley\Database\Migration\Builder;

use InvalidArgumentException;

class Builder
{
    public function id(string $name = 'id')
    {
        if (BuilderMemory::$id !== null) {
            throw new InvalidArgumentException('Table ' . BuilderMemory::$table . ' must have only one primary key');
        }

        BuilderMemory::$id = $name;
    }

    public function varchar(string $name, int $size = 255)
    {
        if (in_array(BuilderMemory::$config['driver'], ['mysql', 'pgsql', 'mariadb'])) {
            $type = sprintf('varchar(%s)', $size);
        } else {
            return $this->typeError('varchar');
        }

        BuilderMemory::addColumn($name, $type);

        return new BuilderOptions(BuilderMemory::$config['driver']);
    }

    public function text(string $name)
    {
        if (in_array(BuilderMemory::$config['driver'], ['mysql', 'pgsql', 'mariadb'])) {
            $type = 'text';
        } else {
            return $this->typeError('text');
        }

        BuilderMemory::addColumn($name, $type);

        return new BuilderOptions(BuilderMemory::$config['driver']);
    }

    public function json(string $name)
    {
        if (in_array(BuilderMemory::$config['driver'], ['mysql', 'pgsql', 'mariadb'])) {
            $type = 'json';
        } else {
            return $this->typeError('json');
        }

        BuilderMemory::addColumn($name, $type);

        return new BuilderOptions(BuilderMemory::$config['driver']);
    }

    public function int(string $name, int|null $size = null)
    {
        if (in_array(BuilderMemory::$config['driver'], ['mysql', 'pgsql', 'mariadb'])) {
            $type = sprintf($size !== null ? 'INT(%s)' : 'INT', $size);
        } else {
            return $this->typeError('int');
        }

        BuilderMemory::addColumn($name, $type);

        return new BuilderOptions(BuilderMemory::$config['driver']);
    }

    public function double(string $name, int $m = 10, int $d = 2)
    {
        if (in_array(BuilderMemory::$config['driver'], ['mysql', 'mariadb'])) {
            $type = sprintf('DOUBLE(%s,%s)', $m, $d);
        } elseif (BuilderMemory::$config['driver'] == 'pgsql') {
            $type = 'DOUBLE PRECISION NULL';
        } else {
            return $this->typeError('double');
        }

        BuilderMemory::addColumn($name, $type);

        return new BuilderOptions(BuilderMemory::$config['driver']);
    }

    public function decimal(string $name, int $m = 10, int $d = 2)
    {
        if (in_array(BuilderMemory::$config['driver'], ['mysql', 'pgsql', 'mariadb'])) {
            $type = sprintf('DECIMAL(%s,%s)', $m, $d);
        } else {
            return $this->typeError('decimal');
        }

        BuilderMemory::addColumn($name, $type);

        return new BuilderOptions(BuilderMemory::$config['driver']);
    }

    public function float(string $name, int $m = 10, int $d = 2)
    {
        if (in_array(BuilderMemory::$config['driver'], ['mysql', 'mariadb'])) {
            $type = sprintf('FLOAT(%s,%s)', $m, $d);
        } elseif (BuilderMemory::$config['driver'] == 'pgsql') {
            $type = 'REAL NULL';
        } else {
            return $this->typeError('float');
        }

        BuilderMemory::addColumn($name, $type);

        return new BuilderOptions(BuilderMemory::$config['driver']);
    }

    public function boolean(string $name)
    {
        if (in_array(BuilderMemory::$config['driver'], ['mysql', 'pgsql', 'mariadb'])) {
            $type = 'BOOLEAN';
        } else {
            return $this->typeError('boolean');
        }

        BuilderMemory::addColumn($name, $type);

        return new BuilderOptions(BuilderMemory::$config['driver']);
    }

    public function timestamp(string $name)
    {
        if (in_array(BuilderMemory::$config['driver'], ['mysql', 'pgsql', 'mariadb'])) {
            $type = 'timestamp';
        } else {
            return $this->typeError('timestamp');
        }

        BuilderMemory::addColumn($name, $type);

        return new BuilderOptions(BuilderMemory::$config['driver']);
    }

    public function date(string $name)
    {
        if (in_array(BuilderMemory::$config['driver'], ['mysql', 'pgsql', 'mariadb'])) {
            $type = 'DATE';
        } else {
            return $this->typeError('date');
        }

        BuilderMemory::addColumn($name, $type);

        return new BuilderOptions(BuilderMemory::$config['driver']);
    }

    public function datetime(string $name)
    {
        if (in_array(BuilderMemory::$config['driver'], ['mysql', 'mariadb'])) {
            $type = 'DATETIME';
        } elseif (BuilderMemory::$config['driver'] == 'pgsql') {
            $type = 'TIMESTAMPTZ';
        } else {
            return $this->typeError('datetime');
        }

        BuilderMemory::addColumn($name, $type);

        return new BuilderOptions(BuilderMemory::$config['driver']);
    }

    public function year(string $name)
    {
        if (in_array(BuilderMemory::$config['driver'], ['mysql', 'mariadb'])) {
            $type = 'YEAR';
        } else if (BuilderMemory::$config['driver'] == 'pgsql') {
            $type = 'INT';
        } else {
            return $this->typeError('year');
        }

        BuilderMemory::addColumn($name, $type);

        return new BuilderOptions(BuilderMemory::$config['driver']);
    }

    public function time(string $name)
    {
        if (in_array(BuilderMemory::$config['driver'], ['mysql', 'pgsql', 'mariadb'])) {
            $type = 'TIME';
        } else {
            return $this->typeError('time');
        }

        BuilderMemory::addColumn($name, $type);

        return new BuilderOptions(BuilderMemory::$config['driver']);
    }

    public function set(string $name, array $values)
    {
        if (in_array(BuilderMemory::$config['driver'], ['mysql', 'mariadb'])) {

            foreach ($values as $key => $value) $values[$key] = "'{$value}'";

            $type = sprintf('SET(%s)', implode(',', $values));
        } else {
            return $this->typeError('set');
        }

        BuilderMemory::addColumn($name, $type);

        return new BuilderOptions(BuilderMemory::$config['driver']);
    }

    public function enum(string $name, array $values)
    {
        if (in_array(BuilderMemory::$config['driver'], ['mysql', 'mariadb'])) {

            foreach ($values as $key => $value) $values[$key] = "'{$value}'";

            $type = sprintf('ENUM(%s)', implode(',', $values));
        } else {
            return $this->typeError('enum');
        }

        BuilderMemory::addColumn($name, $type);

        return new BuilderOptions(BuilderMemory::$config['driver']);
    }

    public function foreign(string $column, string $reference_table, string $reference_column)
    {
        if (in_array(BuilderMemory::$config['driver'], ['mysql', 'pgsql', 'mariadb'])) {
            BuilderMemory::$foreigns[] = [
                'column' => $column,
                'reference_table' => $reference_table,
                'reference_column' => $reference_column,
                'name' => null,
                'on_delete' => null,
                'on_update' => null
            ];
        } else {
            return $this->typeError('foreign');
        }

        return new ForeignOptions(BuilderMemory::$config['driver']);
    }

    /**
     * Columns createdAt and updatedAt
     */
    public function dates()
    {
        if (in_array(BuilderMemory::$config['driver'], ['mysql', 'pgsql', 'mariadb'])) {
            $this->timestamp('created_at')->default('CURRENT_TIMESTAMP', true)->nullable(false);
            $this->timestamp('update_at')->default('CURRENT_TIMESTAMP', true)->onUpdate('CURRENT_TIMESTAMP', true)->nullable(false);
        } else {
            return $this->typeError('dates');
        }
    }

    /**
     * BTREE | FULLTEXT | HASH
     */
    public function index(string|array $column, string|null $name = null, string $type = 'BTREE')
    {
        if ($name === null) $name = 'idx_query_' . BuilderMemory::$table;

        if (in_array(BuilderMemory::$config['driver'], ['mysql', 'pgsql', 'mariadb'])) {
            BuilderMemory::$indexs[$name] = [
                'name' => $name,
                'type' => $type,
                'column' => $column
            ];
        } else {
            return $this->typeError('index');
        }
    }

    public function rename(string $column, string $to)
    {
        if (in_array(BuilderMemory::$config['driver'], ['mysql', 'pgsql', 'mariadb'])) {
            BuilderMemory::$renames[$column] = $to;
        } else {
            return $this->typeError('rename');
        }
    }

    public function raw(string $column, string $value)
    {
        if (in_array(BuilderMemory::$config['driver'], ['mysql', 'pgsql', 'mariadb'])) {
            BuilderMemory::addColumn($column, $value);
        } else {
            return $this->typeError('rename');
        }

        return $this;
    }

    public function dropConstrant(string|array $name)
    {
        if (is_string($name)) $name = [$name];

        if (in_array(BuilderMemory::$config['driver'], ['mysql', 'pgsql', 'mariadb'])) {
            BuilderMemory::$dropConstraints = array_merge($name, BuilderMemory::$dropConstraints);
        } else {
            return $this->typeError('dropConstrant');
        }
    }

    public function dropColumns(string|array $column)
    {
        if (is_string($column)) $column = [$column];

        if (in_array(BuilderMemory::$config['driver'], ['mysql', 'pgsql', 'mariadb'])) {
            BuilderMemory::$dropColumns = array_merge($column, BuilderMemory::$dropColumns);
        } else {
            return $this->typeError('dropColumns');
        }
    }

    public function dropTables(string|array|null $table = null)
    {
        if ($table === null) $table = BuilderMemory::$table;
        if (is_string($table)) $table = [$table];

        if (in_array(BuilderMemory::$config['driver'], ['mysql', 'pgsql', 'mariadb'])) {
            BuilderMemory::$dropTables = array_merge(BuilderMemory::$dropTables, $table);
        } else {
            return $this->typeError('dropTables');
        }
    }

    public function dropIndexs(string|array $name)
    {
        if (is_string($name)) $name = [$name];

        if (in_array(BuilderMemory::$config['driver'], ['mysql', 'pgsql', 'mariadb'])) {
            BuilderMemory::$dropIndexs = array_merge($name, BuilderMemory::$dropIndexs);
        } else {
            return $this->typeError('dropIndexs');
        }
    }

    private function typeError(string $type)
    {
        throw new InvalidArgumentException('Driver does not support the type ' . $type);
    }
}
