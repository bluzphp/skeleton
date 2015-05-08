<?php
/**
 * Created by PhpStorm.
 * User: yuklia
 * Date: 05.05.15
 * Time: 17:30
 */

namespace Application;

use Application\Auth\AuthProvider;
use Application\Users;
use Bluz\Proxy\Messages;
use Bluz\Proxy\Request;

return
    /**
     * @param string $provider
     * @return \closure
     */
    function ($provider = '') {

        /**
         * @var Bootstrap $this
         */
        try {
            $auth = new AuthProvider($provider);
            $auth->setResponse($this);
            $auth->setIdentity($this->user());
            $auth->authProcess();
        } catch (Exception $e) {
            Messages::addError($e->getMessage());
        }

        return
            function () {
                return false;
            };

    };
