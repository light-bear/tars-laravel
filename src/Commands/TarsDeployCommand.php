<?php

namespace LightBear\TarsLaravel\Commands;

use Illuminate\Console\Command;
use Tars\deploy\Deploy;

class TarsDeployCommand extends Command
{
    protected $name = 'tars:deploy';

    public function handle()
    {
        Deploy::handle();
    }
}
