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
     * @var string
     */
    protected $auth_route_regex = '~/auth/([^/]+)/?([^/]+)?~';

    /**
     * @var string
     */
    protected $auth_callback_regex = '';

    /**
     * OpauthMiddleware constructor
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->setConfig($config);
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
            $this->main();
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
        return $this->auth_route_regex;
    }

    /**
     * @param string $auth_route_regex
     */
    public function setAuthRouteRegex($auth_route_regex)
    {
        $this->auth_route_regex = $auth_route_regex;
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
        } elseif (!preg_match($this->getAuthRouteRegex(), $path, $matches)) {
            $trigger = false;
        }

        return $trigger;
    }

    protected function main()
    {
        $this->getOpauth()->run();
    }
}
