<?php

namespace SlimOpauth;

use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class OpauthMiddleware
 * @package StockProfits\Services\Auth
 */
class SlimOpauthMiddleware
{
    /**
     * @var OpauthExtended
     */
    protected $opauth;

    /**
     * @var array
     */
    protected $config = [];

    /**
     * @var UserService
     */
    protected $user_service;
    /**
     * @var string
     */
    const AUTH_ROUTE_REGEX = '~[prefix]([^/]+)/?([^/]+)?~';

    /**
     * @var string
     */
    const AUTH_CALLBACK_REGEX = '~[prefix]callback/?~';

    /**
     * OpauthMiddleware constructor
     * @param array $config
     * @param UserService $user_service
     */
    public function __construct(array $config = [], UserService $user_service)
    {
        $this->setConfig($config);
        $this->setUserService($user_service);
    }

    /**
     * Invoke middleware
     *
     * @param  Request $request Slim request object
     * @param  Response $response Slim response object
     * @param  callable $next Next middleware callable
     *
     * @return Response Slim response object
     */
    public function __invoke(Request $request, Response $response, callable $next)
    {
        if ($this->checkPathQualifies($request)) {
            try {
                $user = $this->main($request);
                $this->getUserService()->handleCallback($request, $response, $user);
            } catch (\Exception $e) {
                $this->getUserService()->handleException($e);
            }
        }

        return $next($request, $response);
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param array $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * @return string
     */
    public function getAuthRouteRegex()
    {
        $path = $this->getConfig()['path'];

        return str_replace('[prefix]', $path, static::AUTH_ROUTE_REGEX);
    }

    /**
     * @return string
     */
    public function getAuthCallbackRegex()
    {
        $path = $this->getConfig()['path'];

        return str_replace('[prefix]', $path, static::AUTH_CALLBACK_REGEX);
    }

    /**
     * @return UserService
     */
    public function getUserService()
    {
        return $this->user_service;
    }

    /**
     * @param UserService $user_service
     */
    public function setUserService($user_service)
    {
        $this->user_service = $user_service;
    }

    /**
     * @return OpauthExtended
     */
    public function getOpauth()
    {
        if (!$this->opauth) {
            $this->opauth = new OpauthExtended($this->getConfig(), false);
        }

        return $this->opauth;
    }

    /**
     * @param Request $request
     * @return bool
     */
    protected function checkPathQualifies(Request $request)
    {
        $trigger = true;
        $path = $request->getUri()->getPath();
        if (!is_string($path)) {
            $trigger = false;
        } elseif (!$this->pathMatchesAuth($path)) {
            $trigger = false;
        }

        return $trigger;
    }

    /**
     * @param string $path
     * @return bool
     */
    protected function pathMatchesAuth($path)
    {
        return !!preg_match($this->getAuthRouteRegex(), $path, $matches);
    }

    /**
     * @param string $path
     * @return bool
     */
    protected function pathMatchesCallback($path)
    {
        return !!preg_match($this->getAuthCallbackRegex(), $path, $matches);
    }

    /**
     * @param Request $request
     * @return array
     */
    protected function main(Request $request)
    {
        $path = $request->getUri()->getPath();
        $user = [];
        if ($this->pathMatchesCallback($path)) {
            $user = $this->getOpauth()->callback();
        } else {
            $this->getOpauth()->run();
        }

        return $user;
    }
}
