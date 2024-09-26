<?php

namespace Haley\Console\Commands;

use Haley\Database\DB;

use Haley\Database\Migration\Builder\BuilderMemory;
use Haley\Database\Query\Scheme;
use Haley\Shell\Lines;
use Haley\Shell\Shell;

class CommandMigration
{
    private $migration = null;

    private BuilderMemory|null $build = null;
    private Scheme|null $scheme = null;
    private string|null $file = null;

    private bool $create = false;

    public function run(string|null $name = null)
    {
        if ($name !== null) {
            $file = directoryRoot('database/migrations/' . $name . '.php');

            if (!file_exists($file)) return Shell::red('file not found')->blue($file)->br();

            $migration_files = [$name . '.php'];
        } else {
            $migration_files = array_diff(scandir(directoryRoot('database/migrations')), ['.', '..']);
        }

        foreach ($migration_files as $file) {
            $this->migration = require directoryRoot('database/migrations/' . $file);
            if (!$this->migration->active) continue;

            $this->file = $file;
            $this->migration->up();
            $this->build = new BuilderMemory;
            $this->build::compileForeigns();

            $this->scheme = DB::scheme($this->build::$connection);

            if ($this->build::$dropTable) {
                if ($this->scheme->table()->has($this->build::$table)) {
                    $drop = $this->scheme->table()->drop($this->build::$table);

                    if ($drop) Shell::green($this->build::$table)->blue('droped')->br();
                }

                $this->build::reset();

                continue;
            }

            if (!$this->scheme->table()->has($this->build::$table)) {
                $this->create = true;

                $this->runCreate();
            }

            $this->runEdit();

            $this->runConstraints();

            $this->build::reset();
            $this->create = false;
        }
    }

    private function runCreate()
    {
        $columns = [];

        foreach ($this->build::getColumns() as $value) {
            $columns[$value['name']] = $value['name'] . ' ' . $value['query'];
        }

        $this->scheme->table()->create($this->build::$table, $columns);

        Shell::green($this->file)->blue('table created')->br();
    }

    private function runEdit()
    {
        // drop coluns
        foreach ($this->build::$dropColumn as $column) {
            if ($this->scheme->column()->has($this->build::$table, $column)) {
                $drop = $this->scheme->column()->drop($this->build::$table, $column);

                if ($drop) Shell::green($this->build::$table . ':' . $column)->blue('droped')->br();
            }
        }

        // change or create columns
        $changes_columns = $this->build::getColumns();

        foreach ($changes_columns as $key => $value) {
            if (in_array($value['name'], $this->build::$dropColumn)) continue;

            if (array_key_exists($key - 1, $changes_columns) and !in_array($changes_columns[$key - 1]['name'], $this->build::$dropColumn)) {
                $value['query'] .= ' AFTER ' . $changes_columns[$key - 1]['name'];
            }

            if ($this->scheme->column()->has($this->build::$table, $value['name'])) {
                $change = $this->scheme->column()->change($this->build::$table, $value['name'], $value['query']);

                if ($change) Shell::green($this->build::$table . ':' . $value['name'])->blue('modified')->br();
            } else {
                $create = $this->scheme->column()->create($this->build::$table, $value['name'], $value['query']);

                if ($create) Shell::green($this->build::$table . ':' . $value['name'])->blue('added')->br();
            }
        }

        // rename columns
        foreach ($this->build::$rename as $column => $to) {
            if ($this->scheme->column()->has($this->build::$table, $column) and !$this->scheme->column()->has($this->build::$table, $to)) {
                $renamed = $this->scheme->column()->rename($this->build::$table, $column, $to);

                if ($renamed) Shell::green($this->build::$table . ':' . $column)->blue('renamed to ' . $to)->br();
            }
        }
    }

    private function runConstraints()
    {
        // column id primary key
        if (count($this->build::$id)) {
            if (!in_array($this->build::$id['name'], $this->build::$dropColumn)) {
                $set = $this->scheme->constraint()->setId($this->build::$table, $this->build::$id['name'], $this->build::$id['comment']);

                if ($set and !$this->create) Shell::green($this->build::$table . ':' . $this->build::$id['name'])->blue('chanhe primary key')->br();
            }
        }

        // drop constraints
        foreach ($this->build::$dropConstraint as $constraint) {
            if ($this->scheme->constraint()->has($this->build::$table, $constraint)) {
                $drop = $this->scheme->constraint()->drop($this->build::$table, $constraint);

                $this->scheme->constraint()->dropIndex($this->build::$table, $constraint);

                if ($drop) Shell::green($this->build::$table . ':constraint:' . $constraint)->blue('droped')->br();
            }
        }

        // set constraints
        foreach ($this->build::$constraints as $constraints) {
            if (in_array($constraints['name'], $this->build::$dropConstraint)) continue;

            if (!$this->scheme->constraint()->has($this->build::$table, $constraints['name'])) {
                $create = $this->scheme->constraint()->create($this->build::$table, $constraints['name'], $constraints['type'], $constraints['value']);

                if ($create) Shell::green($this->build::$table . ':constraint:' . $constraints['name'])->blue('added')->br();
                else  Shell::red($this->build::$table . ':constraint:' . $constraints['name'])->red('fail added')->br();
            } else {
                $this->scheme->constraint()->change($this->build::$table, $constraints['name'], $constraints['type'], $constraints['value']);
            }
        }

        // remove unused constraints
        foreach ($this->build::getColumns() as $column) {
            $constraints = $this->scheme->constraint()->getNamesByColumn($this->build::$table, $column['name']);

            if (!empty($constraints)) foreach ($constraints as $constraint) {
                $drop = true;

                foreach ($this->build::$constraints as $check) if ($constraint == $check['name']) $drop = false;

                if ($drop) {
                    $this->scheme->constraint()->drop($this->build::$table, $constraint);
                    $this->scheme->constraint()->dropIndex($this->build::$table, $constraint);
                }
            }
        }

        // drop index
        foreach ($this->build::$dropIndex as $index) if ($this->scheme->constraint()->hasIndex($this->build::$table, $index)) {
            $this->scheme->constraint()->dropIndex($this->build::$table, $index);
        }


        dd($this->scheme->constraint()->getIndexs($this->build::$table));
        //

        // set indexes
        foreach ($this->build::$index as $index) {
            if (in_array($index['name'], $this->build::$dropIndex)) continue;

            $has = $this->scheme->constraint()->hasIndex($this->build::$table, $index['name']);

            if ($has) $this->scheme->constraint()->dropIndex($this->build::$table, $index['name']);

            $create = $this->scheme->constraint()->addIndex($this->build::$table, $index['column'], $index['name'], $index['type']);
        }
    }

    private function migrationTable()
    {
        if ($this->scheme->table()->has('migrations'));

        $this->scheme->table()->create('migrations', [
            '`id` int NOT NULL AUTO_INCREMENT PRIMARY KEY',
            '`migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL',
            '`batch` int NOT NULL',
        ]);
    }
}
