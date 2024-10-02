<?php

namespace Haley\Console\Commands;

use Haley\Job\JobController;
use Haley\Shell\Shell;

class CommandJobs
{
    private JobController $controller;

    public function __construct()
    {
        $this->controller = new JobController();
    }

    public function process()
    {
        if ($this->controller->running()) {
            $this->stop();
        } else {
            $this->start();
        }
    }

    private function start()
    {
        if ($this->controller->start()) {
            Shell::green('process of jobs running')->br();
        } else {
            Shell::red('failed to start process')->br();
        }
    }

    private function stop()
    {
        if ($this->controller->stop()) {
            Shell::red('process of jobs stopped')->br();
        } else {
            Shell::red('failed to stop process')->br();
        }
    }

    public function execute(string $key)
    {
        $this->controller->execute($key);
    }

    public function master()
    {
        $this->controller->master();
    }

    public function clock()
    {
        $this->controller->clock();
    }

    public function list()
    {
        $list = $this->controller->list();

        foreach ($list as $value) {
            $start = Shell::normal($value['name'] ?? '---', true, false);
            $start .= Shell::gray($value['description'] ?? '?', false, false);

            if ($value['running']) {
                $end = Shell::green('RUNNING', true, false);
            } else {
                $end = Shell::red('STOPPED', true, false);
            }

            Shell::list($start, $end);
        }
    }
}
