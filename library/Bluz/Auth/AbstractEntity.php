<?php

namespace Bluz\Auth;

abstract class AbstractEntity extends \Bluz\Db\Row
{
    /**
     * Get role ID
     *
     * @return mixed
     */
    abstract function getRoleId();

    /**
     * Can entity login
     *
     * @return boolean
     */
    abstract function canLogin();

    public function isEqual($field, $value)
    {
        return $value == $this->{$field};
    }

    /**
     * Login
     */
    public function login()
    {
        global $app;

        $app->getAuth()->setIdentity($this);
    }

    /**
     * Logout
     */
    public function logout()
    {
        global $app;

        $auth = $app->getAuth();

        if ($auth->getIdentity() === $this) {
            $auth->clearIdentity();
        }
    }

}