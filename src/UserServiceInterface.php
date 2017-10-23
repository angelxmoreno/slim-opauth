<?php

namespace SlimOpauth;

use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Interface UserService
 * @package SlimOpauth
 */
interface UserServiceInterface
{
    /**
     * @param \Exception $e
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function handleException(\Exception $e, Request $request, Response $response);

    /**
     * @param UserEntity $user
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function handleCallback(UserEntity $user, Request $request, Response $response);
}
