<?php
/**
 * Created by PhpStorm.
 * User: Madman
 * Date: 2020/11/5
 * Time: 10:40
 */

namespace LightBear\TarsLaravel\Routes;

use Illuminate\Contracts\Http\Kernel;

class LaravelRoute extends Route
{
    protected function handleRequest($illuminateRequest)
    {
        return $this->kernel()->handle($illuminateRequest);
    }

    protected function terminate($illuminateRequest, $illuminateRequest)
    {
        $this->kernel()->terminate($illuminateRequest, $illuminateRequest);
    }

    /**
     * @return Kernel
     */
    protected function kernel()
    {
        return $this->app()->make(Kernel::class);
    }
}