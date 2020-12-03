<?php
/**
 * Created by PhpStorm.
 * User: Madman
 * Date: 2020/11/19
 * Time: 10:05
 */

namespace LightBear\TarsLaravel\Routes;

use Tars\route\DefaultRoute;
use Tars\route\Route as TarsRoute;

class RouteFactory
{
    protected static $defaultRoute = 'laravel';

    protected static $routes = [];

    /**
     * @param string $routeName
     * @return TarsRoute
     */
    public static function getRoute($routeName = ''): TarsRoute
    {
        if (empty($routeName) || $routeName == 'default') {
            $routeName = static::$defaultRoute;
        }

        if (strtolower($routeName) == 'laravel') {
            $routeName = LaravelRoute::class;
        } elseif (strtolower($routeName) == 'lumen') {
            $routeName = LumenRoute::class;
        }

        if (isset(static::$routes[$routeName]) && static::$routes[$routeName] instanceof TarsRoute) {
            return static::$routes[$routeName];
        }

        if (class_exists($routeName)) {
            static::$routes[$routeName] = new $routeName;

            return static::$routes[$routeName];
        } else {
            throw new \RuntimeException('Routing class not found!');
        }
    }

    public static function setDefaultRoute($routeName)
    {
        static::$defaultRoute = $routeName;
    }
}