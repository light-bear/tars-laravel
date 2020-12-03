<?php

namespace LightBear\TarsLaravel;

use Illuminate\Support\ServiceProvider;
use LightBear\TarsLaravel\Commands\Tars2phpCommand;
use LightBear\TarsLaravel\Commands\TarsDeployCommand;
use LightBear\TarsLaravel\Commands\TarsServerCommand;

abstract class TarsServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerRoute();
        $this->registerCommands();
    }

    abstract protected function registerRoute();

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../stubs/index.php' => base_path('index.php'),
            __DIR__ . '/../stubs/services.php' => base_path('services.php'),
            __DIR__ . '/../stubs/config.conf' => base_path('../conf/config.conf'),
            __DIR__ . '/../stubs/tars.proto.php' => base_path('../tars/tars.proto.php'),
            __DIR__ . '/../config/config.php' => base_path('config/tars.php'),
        ], 'tars-laravel');

        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'tars');
    }

    /**
     * Register commands.
     */
    protected function registerCommands()
    {
        $this->commands([
            TarsDeployCommand::class,
            TarsServerCommand::class,
            Tars2phpCommand::class,
        ]);
    }
}
