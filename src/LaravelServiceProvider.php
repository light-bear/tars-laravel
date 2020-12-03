<?php

namespace LightBear\TarsLaravel;

use LightBear\TarsLaravel\Routes\RouteFactory;

class LaravelServiceProvider extends TarsServiceProvider
{
    protected function registerRoute()
    {
        class_alias(RouteFactory::class, \Tars\route\RouteFactory::class);

        RouteFactory::setDefaultRoute('laravel');
    }
}
