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

            // $this->migrationTable();

            if (!$this->scheme->table()->has($this->build::$table)) $this->runCreate();

            $this->runEdit();

            $this->runConstraints();

            $this->build::reset();
        }
    }

    private function runCreate()
    {
        $columns = [];

        foreach ($this->build::getColumns() as $value) {
            $columns[$value['name']] = $value['name'] . ' ' . $value['query'];

            Lines::br()->blue($columns[$value['name']])->br();
        }

        $this->scheme->table()->create($this->build::$table, $columns);

        Shell::green($this->file)->blue('table created')->br();
    }

    private function runEdit()
    {
        // drop coluns
        foreach ($this->build::$dropColumn as $column) {
            if ($this->scheme->column()->has($this->build::$table, $column)) {
                $droped = $this->scheme->column()->drop($this->build::$table, $column);

                if ($droped) Shell::green($this->build::$table . ':' . $column)->blue('droped')->br();
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
                $modified = $this->scheme->column()->change($this->build::$table, $value['name'], $value['query']);

                if ($modified) Shell::green($this->build::$table . ':' . $value['name'])->blue('modified')->br();
            } else {
                $created = $this->scheme->column()->create($this->build::$table, $value['name'], $value['query']);

                if ($created) Shell::green($this->build::$table . ':' . $value['name'])->blue('added')->br();
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
                $change = $this->scheme->constraint()->setId($this->build::$table, $this->build::$id['name'], $this->build::$id['comment']);

                if ($change) Shell::green($this->build::$table . ':' . $this->build::$id['name'])->blue('is primary key')->br();
            }
        }

        // drop constraints
        foreach ($this->build::$dropConstraints as $constraint) {
            if ($this->scheme->constraint()->has($this->build::$table, $constraint)) {
                $change = $this->scheme->constraint()->drop($this->build::$table, $constraint);

                if($change) Shell::green($this->build::$table . ':constraint:' . $constraint)->blue('droped')->br();
            }
        }

        // set constraints
        foreach ($this->build::$constraints as $value) {
            if (in_array($value['name'], $this->build::$dropConstraints)) continue;

            if (!$this->scheme->constraint()->has($this->build::$table, $value['name'])) {
                $this->scheme->constraint()->create($this->build::$table, $value['name'], $value['type'], $value['value']);
            } else {
                $this->scheme->constraint()->change($this->build::$table, $value['name'], $value['type'], $value['value']);
            }
        }

        if (!$this->migration->single) return;

        // remove unused constraints
        // foreach ($this->build::getColumns() as $x) {
        //     $constraints_check = $this->scheme->constraint()->getNamesByColumn($this->build::$table, $x['name']);
        //     // var_dump($this->scheme->constraint()->getNames($this->build::$table));
        //     if (!empty($constraints_check)) {
        //         foreach ($constraints_check as $y) {
        //             // if (!in_array($y, $constraints_active)) {
        //             $this->scheme->constraint()->drop($this->build::$table, $y);
        //             // }
        //         }
        //     }
        // }
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
