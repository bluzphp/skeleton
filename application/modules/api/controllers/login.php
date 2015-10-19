<?php
/**
 * @author   Dmitriy Rassadkin
 * @created  26.09.14 13:02
 */

/**
 * @namespace
 */
namespace Application;

use Bluz\Application\Exception\BadRequestException;
use Bluz\Application\Exception\NotImplementedException;

/**
 * @SWG\Resource(resourcePath="/login")
 * @SWG\Api(
 *   path="/api/login",
 *   @SWG\Operation(
 *      method="POST", nickname="login",
 *      summary="Get Token",
 *      @SWG\Parameter(
 *          name="login",
 *          description="Login",
 *          paramType="form",
 *          required=true,
 *          type="string"
 *      ),
 *      @SWG\Parameter(
 *          name="password",
 *          description="Password",
 *          paramType="form",
 *          required=true,
 *          type="string"
 *      ),
 *      @SWG\ResponseMessage(code=200),
 *      @SWG\ResponseMessage(code=400, message="Login and password are required"),
 *      @SWG\ResponseMessage(code=401, message="User not found")
 *   )
 * )
 */
return
/**
 * @return array
 */
function () {
    /**
     * @var Bootstrap $this
     */
    if ($this->getRequest()->isPost()) {
        $params = $this->getRequest()->getAllParams();

        if (!array_key_exists('login', $params) || !array_key_exists('password', $params)) {
            throw new BadRequestException('Login and password are required');
        }

        //try to authenticate
        $equalsRow = Auth\Table::getInstance()->checkEquals($params['login'], $params['password']);

        //create auth row with token
        $tokenRow = Auth\Table::getInstance()->generateToken($equalsRow);

        return ['token' => $tokenRow->token];
    } else {
        throw new NotImplementedException();
    }
};
