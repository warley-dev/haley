<?php

namespace Haley\Console;

use Haley\Shell\Shell;

class HaleyCommands
{
    public static function run()
    {
        Console::namespace('Haley\Console\Commands')->group(function () {
            Console::command('', 'CommandDashboard::run', false);

            // Console::title('Servers')->prefix('server:')->group(function () {
            Console::command('web {port?}', 'CommandWeb::run')->description('development web server');
            // });

            Console::title('Jobs')->prefix('job:')->group(function () {
                Console::command('active', 'CommandJobs::active')->description('enable or disable jobs ' . self::checkJob());
                Console::command('run {name?}', 'CommandJobs::run')->description('execute jobs');
                Console::command('execute {key}', 'CommandJobs::execute', false);
            });

            Console::title('Migrations')->prefix('migrate')->group(function () {
                Console::command('', 'CommandMigration::run')->description('execute pending migrations');
                Console::command(':reset', 'CommandMigration::run')->description('reset all migrations');
                Console::command(':up {name?}', 'CommandMigration::run')->description('run migration up');
                Console::command(':down {name?}', 'CommandMigration::run')->description('run migration down');
            });

            Console::title('Server')->prefix('server:')->group(function () {
                Console::command('start {name?}', 'CommandServer::start')->description('start servers');
                Console::command('stop {name?}', 'CommandServer::stop')->description('stop servers');
                Console::command('run {name}', 'CommandServer::run')->description('run server');
                Console::command('list', 'CommandServer::list')->description('list servers');
            });

            Console::title('Make')->prefix('make:')->group(function () {
                Console::command('env', 'CommandCreate::env')->description('create a env file');
                Console::command('web {name}', 'CommandCreate::web')->description('create a new web controller');
                Console::command('api {name}', 'CommandCreate::api')->description('create a new api controller');
                Console::command('migration {name}', 'CommandCreate::migration')->description('create a new migration');
                Console::command('model {name} {connection?}', 'CommandCreate::model')->description('create a new model');
                Console::command('job {name}', 'CommandCreate::job')->description('create a new job');
                Console::command('ws {name}', 'CommandCreate::ws')->description('create a new websocket controller');


                // Console::command('middleware {name}', 'CommandCreate::middleware')->description('create a new middleware');

                // Console::command('class {name}', 'CommandCreate::class')->description('create a new');
                // Console::command('model {name} {connection?}', 'CommandCreate::model')->description('create a new model class / name --all to create all models in the database');
            });

            Console::title('Clean')->group(function () {});
        });
    }

    private static function checkJob()
    {
        if (strtolower(PHP_OS) == 'linux') {
            $service = shell_exec('service cron status 2>&1');

            if (str_contains($service, 'running') or str_contains($service, 'active')) {

                $check = shell_exec('crontab -l 2>&1');

                if (!empty($check) and str_contains($check, '* * * * * cd ' . directoryRoot() . ' && php haley job:run >> /dev/null 2>&1')) {
                    return Shell::green('enabled', false, false);
                }
            }
        }

        return Shell::red('disabled', false, false);
    }
}
