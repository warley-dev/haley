<?php

namespace Haley\Console\Commands;

use Haley\Database\Migration\MigrationRunner;
use Haley\Shell\Shell;

class CommandMigration
{
    public function run(string|null $name = null)
    {
        if ($name !== null) {
            $file = directoryRoot('database/migrations/' . $name . '.php');

            if (!file_exists($file)) return Shell::red('file not found')->blue($file)->br();

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

                if ($error['command']) $start .= Shell::gray('[' . $error['command'] . ']', false, false);

                Shell::list($start, $end)->br();
            }

            foreach ($migration->getInfos() as $info) {
                $start = Shell::normal($info['migration'], true, false);
                $end = Shell::green($info['message'], true, false);

                if ($info['command']) $start .= Shell::gray('[' . $info['command'] . ']', false, false);

                Shell::list($start, $end)->br();
            }

            if (count($migration->getErrors()) or count($migration->getInfos())) $updates = true;
        }

        if (!$updates) Shell::red('no pending migrations')->br();
    }
}
