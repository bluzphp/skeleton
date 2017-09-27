<?php
/**
 * Login user and return token
 *
 * @author   Dmitriy Rassadkin
 * @created  26.09.14 13:02
 */

namespace Application;

use Application\Auth;
use Bluz\Application\Exception\BadRequestException;
use Bluz\Controller\Controller;

/**
 * Authorization by login and password
 *
 * @SWG\Post(
 *   path="/api/login",
 *   tags={"authorization"},
 *   operationId="login",
 *   summary="Get access token",
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
 *   @SWG\Response(
 *     response=200,
 *     description="Token"
 *   ),
 *   @SWG\Response(@SWG\Schema(ref="#/definitions/error"), response=400, description="Login and password are required"),
 *   @SWG\Response(@SWG\Schema(ref="#/definitions/error"), response=401, description="Invalid credentials")
 * )
 *
 * @accept JSON
 * @method POST
 *
 * @param string $login
 * @param string $password
 * @return array
 * @throws BadRequestException
 */
return function ($login, $password) {
    /**
     * @var Controller $this
     */
    $this->useJson();

    if (empty($login) || empty($password)) {
        throw new BadRequestException('Login and password are required');
    }

    // try to authenticate
    $authRow = Auth\Provider\Equals::verify($login, $password);

    // get user
    $user = Users\Table::findRow($authRow->userId);

    // create auth token row
    $tokenRow = Auth\Provider\Token::create($user);

    return ['token' => $tokenRow->token];
};
