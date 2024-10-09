<?php

namespace Haley\Console\Commands;

use Haley\Shell\Shell;

class CommandCleaning
{
    public function views()
    {
        $directory = directoryRoot('storage/cache/views');

        if (file_exists($directory)) {
            $files = scandir($directory);
            $files = array_diff($files, array('.', '..'));

            foreach ($files as $arquivo) {
                $path = directoryRoot('storage/cache/views/' . $arquivo);

                if (is_file($path)) deleteFile($path);
            }
        }

        file_put_contents(directoryRoot('storage/cache/jsons/views.json'), json_encode([]));

        Shell::green('views cache deleted')->br();
    }

    public function logs()
    {
        $directory = directoryRoot('storage/logs');

        if (file_exists($directory)) {
            $files = scandir($directory);
            $files = array_diff($files, array('.', '..'));

            foreach ($files as $arquivo) {
                $path = directoryRoot('storage/logs/' . $arquivo);

                if (is_file($path)) deleteFile($path);
            }
        }

        Shell::green('logs deleted')->br();
    }
}
