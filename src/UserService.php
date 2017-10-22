<?php

namespace SlimOpauth;

use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Interface UserService
 * @package SlimOpauth
 */
interface UserService
{
    /**
     * @param \Exception $e
     * @return mixed
     */
    public function handleException(\Exception $e);

    /**
     * @param Request $request
     * @param Response $response
     * @param array $user
     * @return mixed
     */
    public function handleCallback(Request $request, Response $response, array $user);
}