<?php
/**
 * Auth controller
 *
 * @author yuklia
 * @created  05.05.15 17:30
 */
namespace Application;

use Bluz\Proxy\Messages;
use Application\Auth\AuthProvider;

return
    /**
     * @param string $provider
     * @return \closure
     */
    function ($provider = '') {

        /**
         * @var Controller $this
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
