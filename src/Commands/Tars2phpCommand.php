<?php

namespace LightBear\TarsLaravel\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class Tars2phpCommand extends Command
{
    protected $name = 'tars:php';

    protected $description = 'Automatic generation of phptars code';

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->addArgument('proto', InputArgument::REQUIRED, 'Input a proto file name');
    }

    public function handle()
    {
        $argv = [$this->name, $protoFile = $this->argument('proto')];

        // 切换到tars文件目录
        chdir('../tars/');

        if (!file_exists($protoFile)) {
            $this->error("[{$protoFile}] file not found");
            return false;
        }

        /* @var $config Array */
        include base_path('vendor/phptars/tars2php/src/tars2php.php');

        $servantName = $config['appName'] . '.' . $config['serverName'] . '.' . $config['objName'];

        if ($config['withServant']) {
            $this->info("Server[$servantName] generate successfully");
        } else {
            $this->info("Client[$servantName] generate successfully");
        }
    }
}
