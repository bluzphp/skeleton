<?php

namespace Bluz\Auth;

abstract class AbstractEntity extends \Bluz\Db\Row
{
    /**
     * Get role
     *
     * @return \Bluz\Acl\AbstractRole
     */
    abstract function getRole();

    /**
     * Can entity login
     *
     * @return boolean
     */
    abstract function canLogin();

    /**
     * Has user a resource
     *
     * @param string  $type
     * @param integer $id
     * @return boolean
     */
    abstract function hasResource($type, $id);

    /**
     * Is field equal to value
     *
     * @param string $field
     * @param mixed $value
     * @return boolean
     */
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