<?php

namespace Haley\Console\Commands;

use Haley\Database\DB;
use Haley\Shell\Shell;

class CommandMake
{
    public function migration(string $name)
    {
        $table = pathinfo(directoryRoot('database/' . $name), PATHINFO_FILENAME);
        $class = date('Y_m_d_His') . '_' . $table;
        $location = directoryRoot('database/migrations/' . str_replace(basename($name), '', $name) . $class . '.php');

        createDir(dirname($location));

        if (file_exists($location)) {
            Shell::red('replace current file ? (y/n)');

            $response = Shell::readline();

            if ($response != 'y') {
                Shell::red('operation canceled')->br();
                return;
            }
        }

        $template = view('migration', [
            'table' => $name
        ], false, directoryHaley('Templates'));

        file_put_contents($location, $template);

        if (file_exists($location)) {
            Shell::green("migration {$class} created")->normal($location)->br();
        } else {
            Shell::red('error: failed to create migration');
        }
    }

    public function env()
    {
        $location = directoryRoot('.env');

        if (file_exists($location)) {
            Shell::red('replace current file ? (y/n)');

            $response = Shell::readline();

            if ($response != 'y') {
                Shell::red('operation canceled')->br();
                return;
            }
        }

        $template = view('env', [], false, directoryHaley('Templates'));

        file_put_contents($location, $template);

        if (file_exists($location)) {
            Shell::green("env created")->normal($location)->br();
        } else {
            Shell::red('error: failed to create env')->br();
        }
    }

    public function model(string $name, string|null $connection = null)
    {
        $scheme = DB::scheme($connection);
        $all = strtolower($name) == '--all';

        if ($all) {
            $names = $scheme->table()->getNames() ?? [];
        } else {
            $names = [$name];
        }

        if (count($names)) {
            foreach ($names as $name) {
                $params = $this->params($name, directoryRoot('app/Models'));

                if (file_exists($params['path'])) {
                    if ($all) continue;

                    Shell::red('replace current file ? (y/n)');

                    $response = Shell::readline();

                    if ($response != 'y') {
                        Shell::red('operation canceled')->br();
                        return;
                    }
                }

                $primary = null;
                $columns = [];

                if ($scheme->table()->has($params['name'])) {
                    $primary = $scheme->constraint()->getPrimaryKey($params['name']);
                    $columns = $scheme->column()->getNames($params['name']);
                }

                $columns = array_map(function ($column) {
                    return "'$column'";
                }, $columns);

                $template = view('model', [
                    'connection' => $connection,
                    'name' => $params['name'],
                    'namespace' => $params['namespace'],
                    'primary' => $primary,
                    'columns' => $columns
                ], false, directoryHaley('Templates'));

                createDir($params['directory']);

                file_put_contents($params['path'], $template);

                if (file_exists($params['path'])) {
                    Shell::green("model {$params['name']} created")->normal($params['path'])->br();
                } else {
                    Shell::red('error: failed to create model')->br();
                }
            }
        }
    }

    public function job(string $name)
    {
        $params = $this->params($name, directoryRoot('app/Jobs'));

        if (file_exists($params['path'])) {
            Shell::red('replace current file ? (y/n)');

            $response = Shell::readline();

            if ($response != 'y') {
                Shell::red('operation canceled')->br();
                return;
            }
        }

        $template = view('job', [
            'name' => $params['name'],
            'namespace' => $params['namespace']
        ], false, directoryHaley('Templates'));

        createDir($params['directory']);

        file_put_contents($params['path'], $template);

        if (file_exists($params['path'])) {
            Shell::green("job {$params['name']} created")->normal($params['path'])->br();
        } else {
            Shell::red('error: failed to create job')->br();
        }
    }

    public function web(string $name)
    {
        $params = $this->params($name, directoryRoot('app/Controllers/Web'));

        if (file_exists($params['path'])) {
            Shell::red('replace current file ? (y/n)');

            $response = Shell::readline();

            if ($response != 'y') {
                Shell::red('operation canceled')->br();

                return;
            }
        }

        createDir($params['directory']);

        $template = view('web', [
            'name' => $params['name'],
            'namespace' => $params['namespace']
        ], false, directoryHaley('Templates'));

        file_put_contents($params['path'], $template);

        if (file_exists($params['path'])) {
            Shell::green("web controller {$params['name']} created")->normal($params['path'])->br();
        } else {
            Shell::red('error: failed to create web controller')->br();
        }
    }

    public function api(string $name)
    {
        $params = $this->params($name, directoryRoot('app/Controllers/Api'));

        if (file_exists($params['path'])) {
            Shell::red('replace current file ? (y/n)');

            $response = Shell::readline();

            if ($response != 'y') {
                Shell::red('operation canceled')->br();

                return;
            }
        }

        createDir($params['directory']);

        $template = view('api', [
            'name' => $params['name'],
            'namespace' => $params['namespace']
        ], false, directoryHaley('Templates'));

        file_put_contents($params['path'], $template);

        if (file_exists($params['path'])) {
            Shell::green("api controller {$params['name']} created")->normal($params['path'])->br();
        } else {
            Shell::red('error: failed to create api controller')->br();
        }
    }

    public function ws(string $name)
    {
        $params = $this->params($name, directoryRoot('app/Controllers/Server'));

        if (file_exists($params['path'])) {
            Shell::red('replace current file ? (y/n)');

            $response = Shell::readline();

            if ($response != 'y') {
                Shell::red('operation canceled')->br();

                return;
            }
        }

        createDir($params['directory']);

        $template = view('ws', [
            'name' => $params['name'],
            'namespace' => $params['namespace']
        ], false, directoryHaley('Templates'));

        file_put_contents($params['path'], $template);

        if (file_exists($params['path'])) {
            Shell::green("websocket controller {$params['name']} created")->normal($params['path'])->br();
        } else {
            Shell::red('error: failed to create websocket controller')->br();
        }
    }

    public function middleware(string $name)
    {
        $params = $this->params($name, directoryRoot('app/Middlewares'));

        if (file_exists($params['path'])) {
            Shell::red('replace current file ? (y/n)');

            $response = Shell::readline();

            if ($response != 'y') {
                Shell::red('operation canceled')->br();

                return;
            }
        }

        createDir($params['directory']);

        $template = view('middleware', [
            'name' => $params['name'],
            'namespace' => $params['namespace']
        ], false, directoryHaley('Templates'));

        file_put_contents($params['path'], $template);

        if (file_exists($params['path'])) {
            Shell::green("middleware {$params['name']} created")->normal($params['path'])->br();
        } else {
            Shell::red('error: failed to create middleware')->br();
        }
    }

    public function class(string $name)
    {
        $params = $this->params($name, directoryRoot('app/Classes'));

        if (file_exists($params['path'])) {
            Shell::red('replace current file ? (y/n)');

            $response = Shell::readline();

            if ($response != 'y') {
                Shell::red('operation canceled')->br();

                return;
            }
        }

        createDir($params['directory']);

        $template = view('class', [
            'name' => $params['name'],
            'namespace' => $params['namespace']
        ], false, directoryHaley('Templates'));

        file_put_contents($params['path'], $template);

        if (file_exists($params['path'])) {
            Shell::green("class {$params['name']} created")->normal($params['path'])->br();
        } else {
            Shell::red('error: failed to create class')->br();
        }
    }

    public function mail(string $name)
    {
        $params = $this->params($name, directoryRoot('app/Mails'));

        if (file_exists($params['path'])) {
            Shell::red('replace current file ? (y/n)');

            $response = Shell::readline();

            if ($response != 'y') {
                Shell::red('operation canceled')->br();

                return;
            }
        }

        createDir($params['directory']);

        $template = view('mail', [
            'name' => $params['name'],
            'namespace' => $params['namespace']
        ], false, directoryHaley('Templates'));

        file_put_contents($params['path'], $template);

        if (file_exists($params['path'])) {
            Shell::green("mail {$params['name']} created")->normal($params['path'])->br();
        } else {
            Shell::red('error: failed to mail class')->br();
        }
    }

    protected function params(string $name, string $directory)
    {
        $explode = explode('/', $name);

        $name = end($explode);

        unset($explode[array_key_last($explode)]);

        $namespace = implode('\\', $explode);

        if (!strlen($name)) $namespace = null;

        $directory = rtrim(directorySeparator($directory . '/' . implode($explode)), '/');

        return [
            'name' => $name,
            'directory' => $directory,
            'path' => $directory . DIRECTORY_SEPARATOR . $name . '.php',
            'namespace' => $namespace
        ];
    }
}
