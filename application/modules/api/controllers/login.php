<?php

/**
 * Login user and return token
 *
 * @author   Dmitriy Rassadkin
 * @created  26.09.14 13:02
 */

namespace Application;

use Application\Auth;
use Bluz\Controller\Controller;
use Bluz\Http\Exception\BadRequestException;

/**
 * Authorization by login and password
 *
 * @OA\Post(
 *   path="/api/login",
 *   tags={"authorization"},
 *   operationId="login",
 *   summary="Get access token",
 *   @OA\Parameter(
 *     name="login",
 *     description="Login",
 *     in="query",
 *     required=true,
 *     @OA\Schema(type="string")
 *   ),
 *   @OA\Parameter(
 *     name="password",
 *     description="Password",
 *     in="query",
 *     required=true,
 *     @OA\Schema(type="string")
 *   ),
 *   @OA\Response(
 *     @OA\JsonContent(
 *       type="object",
 *       @OA\Property(property="token", type="string", description="Token")
 *     ),
 *     response=200,
 *     description="Token"
 *   ),
 *   @OA\Response(
 *     @OA\JsonContent(ref="#/components/schemas/error"),
 *     response=400,
 *     description="Login and password are required"
 *   ),
 *   @OA\Response(
 *     @OA\JsonContent(ref="#/components/schemas/error"),
 *     response=401,
 *     description="Invalid credentials"
 *   )
 * )
 *
 * @accept JSON
 * @method POST
 *
 * @param string $login
 * @param string $password
 * @return array
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
    $authRow = Auth\Provider\Equals::authenticate($login, $password);

    // get user
    $user = Users\Table::findRow($authRow->userId);

    // create auth token row
    $tokenRow = Auth\Provider\Token::create($user);

    return ['token' => $tokenRow->token];
};
