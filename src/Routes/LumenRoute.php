<?php
/**
 * Created by PhpStorm.
 * User: Madman
 * Date: 2020/11/5
 * Time: 10:40
 */

namespace LightBear\TarsLaravel\Routes;

class LumenRoute extends Route
{
    protected function handleRequest($illuminateRequest)
    {
        return $this->app()->dispatch($illuminateRequest);
    }

    protected function terminate($illuminateRequest, $illuminateResponse)
    {
        $app = $this->app();

        $reflection = new \ReflectionObject($app);

        $middleware = $reflection->getProperty('middleware');
        $middleware->setAccessible(true);

        $callTerminableMiddleware = $reflection->getMethod('callTerminableMiddleware');
        $callTerminableMiddleware->setAccessible(true);

        if (count($middleware->getValue($app)) > 0) {
            $callTerminableMiddleware->invoke($app, $illuminateResponse);
        }
    }
}