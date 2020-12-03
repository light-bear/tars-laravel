<?php

namespace LightBear\TarsLaravel\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Tars\cmd\Command as TarsCommand;

class TarsServerCommand extends Command
{
    protected $name = 'tars:server';

    protected $action;

    protected $config;

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->addArgument('action', InputArgument::OPTIONAL, 'start|stop|restart', 'start');

        $this->addOption('config', 'c', InputOption::VALUE_REQUIRED, 'Path of configuration file');
    }

    public function handle()
    {
        $this->action = $this->argument('action');

        if (!in_array($this->action, ['start', 'stop', 'restart'], true)) {
            $this->error(
                "Invalid argument '{$this->action}'. Expected 'start', 'stop' or 'restart'."
            );

            return;
        }

        $this->config = $this->option('config');

        if (empty($this->config)) {
            $this->error('The "--config" option requires a value.');
            return;
        }

        $command = new TarsCommand($this->action, $this->config);

        $command->run();
    }
}
