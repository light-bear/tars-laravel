<?php
/**
 * Created by PhpStorm.
 * User: Madman
 * Date: 2020/11/5
 * Time: 10:25
 */

namespace LightBear\TarsLaravel\Routes;

use Tars\route\Route AS TarsRoute;
use Tars\core\Request as TarsRequest;
use Tars\core\Response as TarsResponse;
use LightBear\TarsLaravel\Adapters\Request;
use LightBear\TarsLaravel\Adapters\Response;

abstract class Route implements TarsRoute
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Laravel\Lumen\Application|mixed
     */
    protected function app()
    {
        return app();
    }

    public function dispatch(TarsRequest $tarsRequest, TarsResponse $tarsResponse)
    {
        if (Request::handleStatic($tarsRequest, $tarsResponse)) {
            return true;
        }

        $illuminateRequest = Request::make($tarsRequest)->toIlluminate();

        $illuminateResponse = $this->handleRequest($illuminateRequest);

        Response::make($illuminateResponse, $tarsResponse)->send();

        $this->terminate($illuminateRequest, $illuminateResponse);
    }

    abstract protected function handleRequest($illuminateRequest);

    abstract protected function terminate($illuminateRequest, $illuminateResponse);
}