<?php

namespace Haley\Console;

use Haley\Job\JobController;
use Haley\Shell\Shell;

class HaleyCommands
{
    public static function run()
    {
        Console::namespace('Haley\Console\Commands')->group(function () {
            Console::command('', 'CommandDashboard::run', false);

            Console::command('web {port?}', 'CommandWeb::run')->description('development web server');

            Console::title('Makers')->prefix('make:')->group(function () {
                Console::command('env', 'CommandMake::env')->description('create a env file');
                Console::command('web {name}', 'CommandMake::web')->description('create a new web controller');
                Console::command('api {name}', 'CommandMake::api')->description('create a new api controller');
                Console::command('migration {name}', 'CommandMake::migration')->description('create a new migration');
                Console::command('mail {name}', 'CommandMake::mail')->description('create a new mail');
                Console::command('model {name} {connection?}', 'CommandMake::model')->description('[--all to create all models] create a new model');
                Console::command('job {name}', 'CommandMake::job')->description('create a new job');
                Console::command('ws {name}', 'CommandMake::ws')->description('create a new websocket controller');
                Console::command('middleware {name}', 'CommandMake::middleware')->description('create a new middleware');
                Console::command('class {name}', 'CommandMake::class')->description('create a new class');
            });

            Console::title('Migrations')->prefix('migrate')->group(function () {
                Console::command('', 'CommandMigration::run')->description('execute pending migrations');
                Console::command(':reset', 'CommandMigration::reset')->description('reset all migrations');
                Console::command(':up {name}', 'CommandMigration::up')->description('run migration up');
                Console::command(':down {name}', 'CommandMigration::down')->description('run migration down');
            });

            Console::title('Servers')->prefix('server:')->group(function () {
                Console::command('start {name?}', 'CommandServer::start')->description('start servers');
                Console::command('stop {name?}', 'CommandServer::stop')->description('stop servers');
                Console::command('run {name}', 'CommandServer::run')->description('run server');
                Console::command('list', 'CommandServer::list')->description('list servers');
            });

            Console::title('Jobs')->prefix('job:')->group(function () {
                Console::command('process', 'CommandJobs::process')->description('stop or start process ' . self::checkJob());
                Console::command('list', 'CommandJobs::list')->description('list jobs');
                Console::command('clock', 'CommandJobs::clock')->description('executes valid jobs');

                Console::command('master', 'CommandJobs::master', false);
                Console::command('execute {key}', 'CommandJobs::execute', false);
            });

            Console::title('Cleaning')->prefix('clear')->group(function () {
                Console::command(':views', 'CommandCleaning::views')->description('clear views cache');
            });
        });
    }

    private static function checkJob()
    {
        $controller = new JobController();

        if ($controller->running()) return Shell::green('running', false, false);

        return Shell::red('stopped', false, false);
    }
}
