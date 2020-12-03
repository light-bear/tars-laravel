<?php
/**
 * Created by PhpStorm.
 * User: Madman
 * Date: 2020/11/18
 * Time: 10:13
 */

namespace LightBear\TarsLaravel\Adapters;

use Tars\core\Response as TarsResponse;
use Illuminate\Http\Response as IlluminateResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Response
{
    /**
     * @var TarsResponse
     */
    protected $tarsResponse;

    /**
     * @var SymfonyResponse
     */
    protected $illuminateResponse;

    /**
     * Make a response.
     *
     * @param SymfonyResponse $illuminateResponse
     * @param TarsResponse $tarsResponse
     * @return static
     */
    public static function make(SymfonyResponse $illuminateResponse, TarsResponse $tarsResponse)
    {
        return new static($illuminateResponse, $tarsResponse);
    }

    /**
     * Response constructor.
     *
     * @param SymfonyResponse $illuminateResponse
     * @param TarsResponse $tarsResponse
     */
    public function __construct(SymfonyResponse $illuminateResponse, TarsResponse $tarsResponse)
    {
        $this->setIlluminateResponse($illuminateResponse);

        $this->setTarsResponse($tarsResponse);
    }

    /**
     * Sends HTTP headers and content.
     *
     * @throws \InvalidArgumentException
     */
    public function send()
    {
        $this->sendHeaders();
        $this->sendContent();
    }

    /**
     * Sends HTTP headers.
     *
     * @throws \InvalidArgumentException
     */
    protected function sendHeaders()
    {
        $illuminateResponse = $this->getIlluminateResponse();

        /* RFC2616 - 14.18 says all Responses need to have a Date */
        if (!$illuminateResponse->headers->has('Date')) {
            $illuminateResponse->setDate(\DateTime::createFromFormat('U', time()));
        }

        // headers
        foreach ($illuminateResponse->headers->allPreserveCaseWithoutCookies() as $name => $values) {
            foreach ($values as $value) {
                $this->tarsResponse->header($name, $value);
            }
        }

        // status
        $this->tarsResponse->status($illuminateResponse->getStatusCode());

        // cookies
        foreach ($illuminateResponse->headers->getCookies() as $cookie) {
            $method = $cookie->isRaw() ? 'rawcookie' : 'cookie';

            $this->tarsResponse->resource->$method(
                $cookie->getName(), $cookie->getValue(),
                $cookie->getExpiresTime(), $cookie->getPath(),
                $cookie->getDomain(), $cookie->isSecure(),
                $cookie->isHttpOnly()
            );
        }
    }

    /**
     * Sends HTTP content.
     */
    protected function sendContent()
    {
        $illuminateResponse = $this->getIlluminateResponse();

        if ($illuminateResponse instanceof StreamedResponse) {
            $illuminateResponse->sendContent();
        } elseif ($illuminateResponse instanceof BinaryFileResponse) {
            $this->tarsResponse->resource->sendfile($illuminateResponse->getFile()->getPathname());
        } else {
            $this->tarsResponse->resource->end($illuminateResponse->getContent());
        }
    }

    /**
     * @param TarsResponse $tarsResponse
     * @return $this
     */
    protected function setTarsResponse(TarsResponse $tarsResponse)
    {
        $this->tarsResponse = $tarsResponse;

        return $this;
    }

    /**
     * @return tarsResponse
     */
    public function getTarsResponse()
    {
        return $this->tarsResponse;
    }

    /**
     * @param $illuminateResponse
     * @return $this
     */
    protected function setIlluminateResponse($illuminateResponse)
    {
        if (!$illuminateResponse instanceof SymfonyResponse) {
            $content = (string)$illuminateResponse;
            $illuminateResponse = new IlluminateResponse($content);
        }

        $this->illuminateResponse = $illuminateResponse;

        return $this;
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function getIlluminateResponse()
    {
        return $this->illuminateResponse;
    }
}