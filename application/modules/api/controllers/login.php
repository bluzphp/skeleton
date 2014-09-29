<?php
/**
 * @author   Dmitriy Rassadkin
 * @created  26.09.14 13:02
 */

/**
 * @namespace
 */
namespace Application;

use Bluz\Application\Exception\NotImplementedException;
use Bluz\Auth\AuthException;
use Bluz\Controller;

return

function () {
    /**
     * @var Bootstrap $this
     */
    if ($this->getRequest()->isPost()) {
        $params = $this->getRequest()->getAllParams();

        if (!array_key_exists('login', $params) || !array_key_exists('password', $params)) {
            throw new AuthException('Login and password are required', 400);
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
