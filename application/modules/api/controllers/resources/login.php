<?php
/**
 * @author   Dmitriy Rassadkin
 * @created  26.09.14 13:02
 */

/**
 * @namespace
 */

namespace Application;

use Application\Auth;
use Bluz\Application\Exception\BadRequestException;
use Bluz\Application\Exception\NotImplementedException;
use Bluz\Controller\Controller;
use Bluz\Proxy\Request;

/**
 * @SWG\Post(
 *   path="/api/login",
 *   tags={"authorization"},
 *   operationId="login",
 *   summary="Get Token",
 *   @SWG\Parameter(
 *       name="login",
 *       in="formData",
 *       description="Login",
 *       required=true,
 *       type="string"
 *   ),
 *   @SWG\Parameter(
 *       name="password",
 *       in="formData",
 *       description="Password",
 *       required=true,
 *       type="string"
 *   ),
 *   @SWG\Response(response=200, description="Token"),
 *   @SWG\Response(response=400, description="Login and password are required"),
 *   @SWG\Response(response=401, description="User not found")
 * )
 * @return array
 * @throws BadRequestException
 * @throws NotImplementedException
 * @throws \Bluz\Auth\AuthException
 */
return function () {
    /**
     * @var Controller $this
     */
    if (Request::isPost()) {
        $params = Request::getParams();

        if (!array_key_exists('login', $params) || !array_key_exists('password', $params)) {
            throw new BadRequestException('Login and password are required');
        }

        // try to authenticate
        $authRow = Auth\EqualsProvider::verify($params['login'], $params['password']);

        // get user
        $user = Users\Table::findRow($authRow->userId);

        // create auth token row
        $tokenRow = Auth\TokenProvider::create($user);

        return ['token' => $tokenRow->token];
    }
    throw new NotImplementedException();
};
