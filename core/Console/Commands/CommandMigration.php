<?php

namespace Haley\Console\Commands;

use Haley\Database\DB;
use Haley\Database\Migration\MigrationRunner;
use Haley\Shell\Lines;
use Haley\Shell\Shell;
use Throwable;

class CommandMigration
{
    public function run(string|null $name = null)
    {
        if ($name !== null) {
            $file = directoryRoot('database/migrations/' . $name . '.php');

            if (!file_exists($file)) return Shell::red('file not found')->normal($file)->br();

            $migration_files = [$name . '.php'];
        } else {
            $migration_files = array_diff(scandir(directoryRoot('database/migrations')), ['.', '..']);
        }

        $updates = false;

        foreach ($migration_files as $file) {
            $migration = new MigrationRunner();

            $migration->run($file);

            foreach ($migration->getErrors() as $error) {
                $start = Shell::normal($error['migration'], true, false);
                $end = Shell::red($error['message'], true, false);

                if ($error['table']) $start .= Shell::gray('[' . $error['table'] . ']', false, false);

                Shell::list($start, $end)->br();
            }

            foreach ($migration->getInfos() as $info) {
                $start = Shell::normal($info['migration'], true, false);
                $end = Shell::green($info['message'], true, false);

                if ($info['table']) $start .= Shell::gray('[' . $info['table'] . ']', false, false);

                Shell::list($start, $end)->br();
            }

            if (count($migration->getErrors()) or count($migration->getInfos())) $updates = true;
        }

        if (!$updates) Shell::red('no pending migrations')->br();
    }

    public function up(string $name)
    {
        $file = directoryRoot('database/migrations/' . $name . '.php');

        if (!file_exists($file)) return Shell::red('file not found')->normal($file)->br();

        $migration = new MigrationRunner();

        $migration->run($file, true);

        $updates = false;

        foreach ($migration->getErrors() as $error) {
            $start = Shell::normal($error['migration'], true, false);
            $end = Shell::red($error['message'], true, false);

            if ($error['table']) $start .= Shell::gray('[' . $error['table'] . ']', false, false);

            Shell::list($start, $end)->br();
        }

        foreach ($migration->getInfos() as $info) {
            $start = Shell::normal($info['migration'], true, false);
            $end = Shell::green($info['message'], true, false);

            if ($info['table']) $start .= Shell::gray('[' . $info['table'] . ']', false, false);

            Shell::list($start, $end)->br();
        }

        if (count($migration->getErrors()) or count($migration->getInfos())) $updates = true;

        if (!$updates) Shell::red('no pending migration')->br();
    }

    public function down(string $name)
    {
        $file = directoryRoot('database/migrations/' . $name . '.php');

        if (!file_exists($file)) return Shell::red('file not found')->normal($file)->br();

        $migration = new MigrationRunner();

        $migration->run($file, true, 'down');

        $updates = false;

        foreach ($migration->getErrors() as $error) {
            $start = Shell::normal($error['migration'], true, false);
            $end = Shell::red($error['message'], true, false);

            if ($error['table']) $start .= Shell::gray('[' . $error['table'] . ']', false, false);

            Shell::list($start, $end)->br();
        }

        foreach ($migration->getInfos() as $info) {
            $start = Shell::normal($info['migration'], true, false);
            $end = Shell::green($info['message'], true, false);

            if ($info['table']) $start .= Shell::gray('[' . $info['table'] . ']', false, false);

            Shell::list($start, $end)->br();
        }

        if (count($migration->getErrors()) or count($migration->getInfos())) $updates = true;

        if (!$updates) Shell::red('no pending migration')->br();
    }

    public function reset(string|null $connection = null)
    {
        Shell::red('Are you sure you want to reset the database ? (y/n)');

        $response = Shell::readline();

        if ($response != 'y') {
            Shell::red('operation canceled')->br();

            return;
        }

        $scheme = DB::scheme($connection);

        $tables = $scheme->table()->getNames();

        while (count($tables)) {
            foreach ($tables as $key => $table) {
                try {
                    $drop = $scheme->table()->drop($table);

                    if ($drop) unset($table[$key]);
                } catch (Throwable $e) {
                    $tables = $scheme->table()->getNames();
                }
            }
        }

        $this->run();
    }

    public function seed(string|null $name)
    {
        if ($name !== null) {
            $file = directoryRoot('database/seeders/' . $name . '.php');

            if (!file_exists($file)) return Shell::red('file not found')->normal($file)->br();

            $migration_files = [$name . '.php'];
        } else {
            $migration_files = array_diff(scandir(directoryRoot('database/seeders')), ['.', '..']);
        }

        foreach ($migration_files as $file) {
            try {
                $path = directoryRoot('database/seeders/' . $file);

                $seed = require $path;

                $seed->run();
            } catch (Throwable $error) {
                $e = Lines::red($error->getMessage(), false, false);

                Shell::list(str_replace('.php', '', $file), $e)->br();
            }
        }

        Shell::green('seeders run')->br();
    }
}
