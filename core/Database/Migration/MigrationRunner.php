<?php

namespace Haley\Database\Migration;

use Haley\Collections\Config;
use Haley\Database\Connection;
use Haley\Database\DB;
use Haley\Database\Migration\Builder\BuildColumn;
use Haley\Database\Migration\Builder\Builder;
use Haley\Database\Migration\Builder\BuilderMemory;
use Haley\Database\Query\Scheme;
use Throwable;

class MigrationRunner
{
    private array $errors = [];
    private array $infos = [];

    private string $name;
    private string $file;

    private string|null $connection;
    private array|null $config = null;

    private $migration;
    private BuilderMemory|null $build = null;
    private Scheme|null $scheme = null;
    private BuildColumn|null $column = null;

    public function run(string $migration, bool $force = false, $type = 'up')
    {
        BuilderMemory::reset();

        if (!in_array($type, ['up', 'down'])) {
            $this->addError('method ' . $type . ' migration does not exist');

            return;
        }

        $this->name = str_replace('.php', '', basename($migration));
        $this->file = directoryRoot('database/migrations/' . $this->name . '.php');

        if (!file_exists($this->file)) {
            $this->addError('migration not fount');

            return;
        }

        try {
            $this->migration = require $this->file;
        } catch (Throwable) {
            $this->addError('failed to load class');

            return;
        }

        if (!method_exists($this->migration, 'up')) {
            $this->addError('the up method does not exist');

            return;
        }

        if (!method_exists($this->migration, 'down')) {
            $this->addError('the down method does not exist');

            return;
        }

        $this->config = Connection::config($this->migration->connection);
        $this->connection = $this->config['name'];
        $this->column = new BuildColumn($this->config, $this->migration->table);

        BuilderMemory::$connection = $this->connection;
        BuilderMemory::$config = $this->config;
        BuilderMemory::$table = $this->migration->table;

        $builder = new Builder();

        $this->build = new BuilderMemory;
        $this->build::compileForeigns();

        $this->scheme = DB::scheme($this->build::$connection);
        $this->migrationTable();

        // check migration db
        $check = DB::table('migrations')->select(['id', 'count'])->where('migration', $this->name)->first();

        if (!empty($check->id)) {
            if ($force) {
                DB::table('migrations')->connection($this->build::$connection)->where('id', $check->id)->update([
                    'count' => $check->count + 1
                ]);
            } else {
                return;
            }
        } else {
            DB::table('migrations')->connection($this->build::$connection)->insert([
                'count' => 1,
                'migration' => $this->name
            ]);
        }

        try {
            if ($type === 'up') $this->migration->up($builder);
            if ($type === 'down') $this->migration->down($builder);

            $this->exec();
        } catch (Throwable $error) {
            $this->addError($error->getMessage());

            return;
        }
    }

    private function migrationTable()
    {
        if (!$this->scheme->table()->has('migrations')) {
            if ($this->config['driver'] == 'pgsql') {
                $columns = [
                    $this->quotes('id') . ' SERIAL NOT NULL',
                    $this->quotes('migration') . ' varchar(255) DEFAULT NULL UNIQUE',
                    $this->quotes('count') . ' INT',
                    $this->quotes('created_at') . ' timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
                    'PRIMARY KEY (' . $this->quotes('id') . ')'
                ];
            } else {
                $columns = [
                    $this->quotes('id') . ' int NOT NULL AUTO_INCREMENT',
                    $this->quotes('migration') . ' varchar(255) DEFAULT NULL UNIQUE',
                    $this->quotes('count') . ' INT',
                    $this->quotes('created_at') . ' timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
                    'PRIMARY KEY (' . $this->quotes('id') . ')'
                ];
            }

            $create = $this->scheme->table()->create('migrations', $columns);

            dd($create);
        }
    }

    private function exec()
    {
        // drop table
        foreach ($this->build::$dropTable as $table) {
            if ($this->scheme->table()->has($table)) {
                try {
                    $drop = $this->scheme->table()->drop($table);
                } catch (Throwable $error) {
                    $drop = false;

                    $this->addError($error->getMessage());
                }

                if ($drop) $this->addInfo("table $table droped");
                else $this->addError("table $table failed to drop");
            } else {
                $this->addError("table $table does not exist");
            }
        }

        if ($this->build::$table === null) return;

        if (in_array($this->build::$table, $this->build::$dropTable)) return;

        // create or edit
        if (!$this->scheme->table()->has($this->build::$table)) {
            $this->createTable();
        } else {
            $this->editTable();
        }

        // constraints
        $this->runConstraints();
    }

    private function createTable()
    {
        $columns = [];
        $others = [];

        foreach ($this->build::getColumns() as $value) {
            $querys = $this->column->create($value['name'], $value['type'], $value['options']);

            $columns = array_merge($columns, $querys['columns']);
            $others = array_merge($others, $querys['others']);
        }

        // dd($columns);

        $create = $this->scheme->table()->create($this->build::$table, $columns);

        if ($create) {
            $this->addInfo("table {$this->build::$table} created");

            foreach ($others as $query) DB::connection($this->connection)->query($query)->execute();
        } else {
            $this->addInfo("failed to create table {$this->build::$table}");
        }
    }

    private function editTable()
    {
        // drop coluns
        foreach ($this->build::$dropColumn as $column) {
            if ($this->scheme->column()->has($this->build::$table, $column)) {
                try {
                    $drop = $this->scheme->column()->drop($this->build::$table, $column);
                } catch (Throwable $error) {
                    $drop = false;

                    $this->addError($error->getMessage());
                }

                if ($drop) $this->addInfo("column $column droped");
                else $this->addError("failed to drop column $column");
            } else {
                $this->addError("column $column does not exist");
            }
        }

        // change or create coluns
        foreach ($this->build->getColumns() as $column) {
            if (in_array($column['name'], $this->build::$dropColumn)) continue;

            if ($this->scheme->column()->has($this->build::$table, $column['name'])) {
                $querys = $this->column->edit($column['name'], $column['type'], $column['options'], false);

                dd($querys);

                foreach ($querys['columns'] as $type) {
                    $change = $this->scheme->column()->change($this->build::$table, $column['name'], $type);

                    if ($change) $this->addInfo("column {$column['name']} changed");

                    foreach ($querys['others'] as $query) {
                        DB::connection($this->connection)->query($query)->execute();
                    }
                }



            } else {
                $querys = $this->column->create($column['name'], $column['type'], $column['options'], false);

                foreach ($querys['columns'] as $type) {
                    $create = $this->scheme->column()->create($this->build::$table, $column['name'], $type);

                    if ($create) {
                        $this->addInfo("column {$column['name']} added");

                        foreach ($querys['others'] as $query) {
                            DB::connection($this->connection)->query($query)->execute();
                        }
                    };
                }
            }
        }

        // rename columns
        foreach ($this->build::$rename as $column => $to) {
            if ($this->scheme->column()->has($this->build::$table, $column) and !$this->scheme->column()->has($this->build::$table, $to)) {
                $renamed = $this->scheme->column()->rename($this->build::$table, $column, $to);

                if ($renamed) $this->addInfo("column $column renamed");
            }
        }
    }

    private function runConstraints()
    {
        // column id primary key
        if (count($this->build::$id)) {
            if (!in_array($this->build::$id['name'], $this->build::$dropColumn)) {
                $set = $this->scheme->constraint()->setId($this->build::$table, $this->build::$id['name'], $this->build::$id['comment']);

                if ($set) $this->addInfo("primary key {$this->build::$id['name']} defined");
            }
        }

        // drop constraints
        foreach ($this->build::$dropConstraint as $constraint) {
            if ($this->scheme->constraint()->has($this->build::$table, $constraint)) {
                $drop = $this->scheme->constraint()->drop($this->build::$table, $constraint);

                if ($drop) $this->addInfo("constraint $constraint droped");
            }
        }

        // set constraints
        foreach ($this->build::$constraints as $constraints) {
            if (in_array($constraints['name'], $this->build::$dropConstraint)) continue;

            if (!$this->scheme->constraint()->has($this->build::$table, $constraints['name'])) {
                $create = $this->scheme->constraint()->create($this->build::$table, $constraints['name'], $constraints['type'], $constraints['value']);

                if ($create) $this->addInfo("constraint {$constraints['name']} added");
            } else {
                $this->scheme->constraint()->change($this->build::$table, $constraints['name'], $constraints['type'], $constraints['value']);
            }
        }

        // set indexes
        foreach ($this->build::$index as $index) {
            if (in_array($index['name'], $this->build::$dropIndex)) continue;

            $has = $this->scheme->constraint()->hasIndex($this->build::$table, $index['name']);

            if ($has) $this->scheme->constraint()->dropIndex($this->build::$table, $index['name']);

            $create = $this->scheme->constraint()->addIndex($this->build::$table, $index['column'], $index['name'], $index['type']);

            if ($create) $this->addInfo("index {$index['name']} added");
        }
    }

    private function addError(string $message)
    {
        $this->errors[] = [
            'migration' => $this->name,
            'message' => $message,
            'table' => $this->build::$table
        ];
    }

    private function addInfo(string $message)
    {
        $this->infos[] = [
            'migration' => $this->name,
            'message' => $message,
            'table' => $this->build::$table
        ];
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getInfos()
    {
        return $this->infos;
    }

    private function buildColumnCreate() {}

    private function quotes(string $string)
    {
        $string = preg_replace('/\b(?!as\b)(\w+)\b/i', $this->config['quotes'] . '$1' . $this->config['quotes'], $string);
        $string = preg_replace('/(' . preg_quote($this->config['quotes']) . ')\s/', '$1 ', $string);

        return $string;
    }
}
