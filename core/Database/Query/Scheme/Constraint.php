<?php

namespace Haley\Database\Query\Scheme;

use Haley\Database\DB;
use InvalidArgumentException;
use PDO;

class Constraint
{
    private array $config = [];

    public function __construct(array $config)
    {
        $this->config = $config;

        if ($this->config['driver'] == 'pgsql' and !empty($config['search_path'])) {
            $this->config['database'] = $config['search_path'];
        }
    }

    /**
     * @return bool
     */
    public function has(string $table, string $name)
    {
        if (in_array($this->config['driver'], ['mysql', 'pgsql', 'mariadb'])) {
            $query = DB::query('SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND CONSTRAINT_NAME = ?', [$this->config['database'], $table, $name], $this->config['name'])->fetch(PDO::FETCH_ASSOC);
            if (!empty($query)) return true;
        } else {
            $this->driverError($this->config['driver']);
        }

        return false;
    }

    /**
     * @return array|null
     */
    public function getSchema(string $table, string $name)
    {
        if (in_array($this->config['driver'], ['mysql', 'pgsql', 'mariadb'])) {
            $query = DB::query('SELECT * FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND CONSTRAINT_NAME = ?', [$this->config['database'], $table, $name], $this->config['name'])->fetch(PDO::FETCH_ASSOC);
            if (!empty($query)) return $query;
        } else {
            $this->driverError($this->config['driver']);
        }

        return null;
    }

    /**
     * @return array
     */
    public function getNamesByType(string $table, string $type)
    {
        $names = [];

        if (in_array($this->config['driver'], ['mysql', 'pgsql', 'mariadb'])) {
            $query = DB::query('SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND CONSTRAINT_TYPE = ?', [$this->config['database'], $table, $type], $this->config['name'])->fetchAll(PDO::FETCH_ASSOC);
            if (count($query)) foreach ($query as $value) $names[] = $value['constraint_name'] ?? $value['CONSTRAINT_NAME'];
        } else {
            $this->driverError($this->config['driver']);
        }

        return $names;
    }

    /**
     * @return array
     */
    public function getNamesByColumn(string $table, string $column)
    {
        $names = [];

        if (in_array($this->config['driver'], ['mysql', 'pgsql', 'mariadb'])) {
            $query = DB::query('SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? AND CONSTRAINT_NAME IS NOT NULL', [$this->config['database'], $table, $column], $this->config['name'])->fetchAll(PDO::FETCH_ASSOC);

            if (count($query)) foreach ($query as $value) $names[] = $value['constraint_name'] ?? $value['CONSTRAINT_NAME'];
        } else {
            $this->driverError($this->config['driver']);
        }

        return $names;
    }

    /**
     * @return array|null
     */
    public function getNames(string $table)
    {
        $names = [];

        if (in_array($this->config['driver'], ['mysql', 'pgsql', 'mariadb'])) {
            $query = DB::query('SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?', [$this->config['database'], $table], $this->config['name'])->fetchAll(PDO::FETCH_ASSOC);
            if (count($query)) foreach ($query as $value) $names[] = $value['constraint_name'] ?? $value['CONSTRAINT_NAME'];
        } else {
            $this->driverError($this->config['driver']);
        }

        if (count($names)) return $names;

        return null;
    }

    /**
     * Drop constraint
     * @return bool
     */
    public function drop(string $table, string $name)
    {
        if (in_array($this->config['driver'], ['mysql', 'pgsql', 'mariadb'])) {
            DB::query(sprintf('ALTER TABLE %s DROP CONSTRAINT %s', $this->quotes($table), $this->quotes($name)), connection: $this->config['name'])->fetch(PDO::FETCH_OBJ);
        } else {
            $this->driverError($this->config['driver']);
        }

        return !$this->has($table, $name);
    }

    /**
     * @return bool
     */
    public function create(string $table, string $name, string $type, string $value)
    {
        if (in_array($this->config['driver'], ['mysql', 'pgsql', 'mariadb'])) {
            DB::query(sprintf('ALTER TABLE %s ADD CONSTRAINT %s %s %s', $this->quotes($table), $this->quotes($name), $type, $value), connection: $this->config['name']);
        } else {
            $this->driverError($this->config['driver']);
        }

        return $this->has($table, $name);
    }

    /**
     * @return bool */
    public function change(string $table, string $name, string $type, string $value)
    {
        $old = $this->getSchema($table, $name);

        if (in_array($this->config['driver'], ['mysql', 'pgsql', 'mariadb'])) {
            if ($this->has($table, $name)) $this->drop($table, $name);
            DB::query(sprintf('ALTER TABLE %s ADD CONSTRAINT %s %s %s', $this->quotes($table), $this->quotes($name), $type, $value), connection: $this->config['name']);
        } else {
            $this->driverError($this->config['driver']);
        }

        $new = $this->getSchema($table, $name);

        if (empty($new)) return false;

        $difference = array_diff($new, $old);

        if (!count($difference)) return false;

        return true;
    }

    /**
     * Set column id - Primary key
     */
    public function setId(string $table, string $column)
    {
        $atual = $this->getPrimaryKey($table);

        if ($atual == $column) return false;

        if ($atual) {
            if ($this->config['driver'] != 'pgsql') {
                DB::scheme($this->config['name'])->column()->change($table, $atual, 'INT NOT NULL');
            } else {
                // DB::scheme($this->config['name'])->column()->change($table, $atual, 'TYPE SERIAL');
            }

            $this->dropPrimaryKey($table);
        }

        $exists = DB::scheme($this->config['name'])->column()->has($table, $column);

        if ($exists) {
            if ($this->config['driver'] != 'pgsql') {
                DB::scheme($this->config['name'])->column()->change($table, $column, 'INT NOT NULL AUTO_INCREMENT FIRST');
                if ($atual !== $column) $this->setPrimaryKey($table, $column);
            } else {
                DB::scheme($this->config['name'])->column()->change($table, $column, 'TYPE SERIAL');
            }
        } else {

            if ($this->config['driver'] != 'pgsql') {
                DB::scheme($this->config['name'])->column()->create($table, $column, 'INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST');
            } else {
                DB::scheme($this->config['name'])->column()->create($table, $column, 'SERIAL PRIMARY KEY');
            }
        }

        return $this->getPrimaryKey($table) == $column;
    }

    /**
     * Define primary key of the table
     * @return bool
     */
    public function setPrimaryKey(string $table, string $column)
    {
        if (in_array($this->config['driver'], ['mysql', 'pgsql', 'mariadb'])) {
            DB::query(sprintf('ALTER TABLE %s ADD PRIMARY KEY %s(%s)', $this->quotes($table), 'PRIMARY', $this->quotes($column)), connection: $this->config['name']);
        } else {
            $this->driverError($this->config['driver']);
        }

        return $this->getPrimaryKey($table) == $column;
    }

    /**
     * Get PrimaryKey from the table
     * @return string|null
     */
    public function getPrimaryKey(string $table)
    {
        if (in_array($this->config['driver'], ['mysql', 'pgsql', 'mariadb'])) {
            $query = DB::query("SELECT column_name FROM information_schema.key_column_usage WHERE table_name = ? AND TABLE_SCHEMA = ? AND constraint_name = (SELECT constraint_name FROM information_schema.table_constraints WHERE table_name = ? AND constraint_type = 'PRIMARY KEY' AND TABLE_SCHEMA = ?)", [$table, $this->config['database'], $table, $this->config['database']],  $this->config['name'])->fetch(PDO::FETCH_ASSOC);

            if (!empty($query)) return $query['COLUMN_NAME'] ?? $query['column_name'];
        } else {
            $this->driverError($this->config['driver']);
        }

        return null;
    }

    /**
     * Drop PrimaryKey from the table
     * @return bool
     */
    public function dropPrimaryKey(string $table)
    {
        if (in_array($this->config['driver'], ['mysql', 'mariadb'])) {
            DB::query(sprintf('ALTER TABLE %s DROP PRIMARY KEY', $this->quotes($table)), [],  $this->config['name'])->fetch(PDO::FETCH_ASSOC);
        } else if ($this->config['driver'] == 'pgsql') {
            $names = $this->getNamesByType($table, 'PRIMARY KEY');

            foreach ($names as $name) $this->drop($table, $name);
        }

        return $this->getPrimaryKey($table) === null;
    }

    /**
     * @return bool
     */
    public function hasIndex(string $table, string $name)
    {
        if (in_array($this->config['driver'], ['mysql',  'mariadb'])) {
            $query = DB::query('SELECT INDEX_NAME FROM INFORMATION_SCHEMA.STATISTICS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND INDEX_NAME = ?', [$this->config['database'], $table, $name], $this->config['name'])->fetch(PDO::FETCH_ASSOC);

            if (!empty($query)) return true;
        } else if ($this->config['driver'] == 'pgsql') {
            $query = DB::query('SELECT indexname FROM pg_indexes WHERE schemaname = ? AND tablename = ? AND indexname = ?', [$this->config['database'], $table, $name], $this->config['name'])->fetch(PDO::FETCH_ASSOC);

            if (!empty($query)) return true;
        } else {
            $this->driverError($this->config['driver']);
        }

        return false;
    }

    /**
     * @return bool
     */
    public function dropIndex(string $table, string $name)
    {
        if (!$this->hasIndex($table, $name)) return false;

        if (in_array($this->config['driver'], ['mysql', 'mariadb'])) {
            DB::query(sprintf('DROP INDEX %s ON %s', $this->quotes($name), $this->quotes($table)), [], $this->config['name'])->fetch(PDO::FETCH_ASSOC);
        } else if ($this->config['driver'] == 'pgsql') {
            DB::query(sprintf('DROP INDEX IF EXISTS %s CASCADE', $this->quotes($name)), [], $this->config['name'])->fetch(PDO::FETCH_ASSOC);
        } else {
            $this->driverError($this->config['driver']);
        }

        return !$this->hasIndex($table, $name);
    }

    /**
     * BTREE | FULLTEXT | HASH
     * @return bool
     */
    public function addIndex(string $table, string|array $column, string $name, string $type = 'BTREE')
    {
        if (is_string($column)) $column = [$column];

        if (in_array($this->config['driver'], ['mysql', 'pgsql', 'mariadb'])) {
            foreach ($column as $key => $value) $column[$key] = $this->quotes($value);

            $column = implode(',', $column);

            if ($type == 'BTREE') {
                $query = sprintf('CREATE INDEX %s ON %s(%s)', $this->quotes($name), $this->quotes($table), $column);
            } elseif ($type == 'FULLTEXT') {
                $query = sprintf('CREATE FULLTEXT INDEX %s ON %s(%s)', $this->quotes($name), $this->quotes($table), $column);
            } else if ($type == 'HASH') {
                $query = sprintf('CREATE INDEX %s ON %s(%s) USING HASH', $this->quotes($name), $this->quotes($table), $column);
            }

            DB::query($query, [], $this->config['name'])->fetch(PDO::FETCH_ASSOC);
        } else {
            $this->driverError($this->config['driver']);
        }

        return $this->hasIndex($table, $name);
    }

    /**
     * @return array
     */
    public function getIndexs(string $table)
    {
        $data = [];

        if (in_array($this->config['driver'], ['mysql', 'mariadb'])) {
            $result = DB::query('SELECT INDEX_NAME FROM INFORMATION_SCHEMA.STATISTICS WHERE INDEX_NAME != ? AND TABLE_SCHEMA = ? AND TABLE_NAME = ? AND NULLABLE = ?', ['PRIMARY', $this->config['database'], $table, 'YES'], $this->config['name'])->fetchAll(PDO::FETCH_ASSOC);

            foreach ($result as $value) $data[] = $value['INDEX_NAME'];
        } else if ($this->config['driver'] == 'pgsql') {
            $result = DB::query('SELECT indexname FROM pg_indexes WHERE schemaname = ? AND tablename = ?', [$this->config['database'], $table], $this->config['name'])->fetchAll(PDO::FETCH_ASSOC);

            foreach ($result as $value)  $data[] = $value['indexname'];
        } else {
            $this->driverError($this->config['driver']);
        }

        return $data;
    }

    private function quotes(string $string)
    {
        $string = preg_replace('/\b(?!as\b)(\w+)\b/i', $this->config['quotes'] . '$1' . $this->config['quotes'], $string);
        $string = preg_replace('/(' . preg_quote($this->config['quotes']) . ')\s/', '$1 ', $string);

        return $string;
    }

    private function driverError(string $driver)
    {
        throw new InvalidArgumentException('Driver not found for ' . $driver);
    }
}
