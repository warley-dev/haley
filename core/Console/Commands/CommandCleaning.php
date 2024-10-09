<?php

namespace Haley\Console\Commands;

use Haley\Shell\Shell;

class CommandCleaning
{
    public function views()
    {
        $files = directoryRoot('storage/cache/views');
        $json = directoryRoot('storage/cache/jsons/views.json');

        deleteDir($files);
        deleteFile($json);

        if (!file_exists($files) and !file_exists($json)) {
            Shell::green('views cache deleted')->br();
        } else {
            Shell::red('failed to clear views cache')->br();
        }
    }
}
