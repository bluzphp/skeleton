<?php
/**
 * Adapter
 *
 * @category Bluz
 * @package  Auth
 *
 * @author   Anton Shevchuk
 * @created  12.07.11 15:33
 */
namespace Bluz\Auth;

abstract class AbstractAdapter
{
    use \Bluz\Package;

    /**
     * @var Auth
     */
    protected $auth;

    /**
     * authenticate
     *
     * @param string $login
     * @param string $password
     * @param \Bluz\Auth\AbstractEntity $entity
     * @return bool
     */
    abstract function authenticate($login, $password, \Bluz\Auth\AbstractEntity $entity = null);

    /**
     * setAuth
     *
     * @param $auth
     * @return Table
     */
    public function setAuth($auth)
    {
        $this->auth = $auth;
        return $this;
    }

    /**
     * getAuth
     *
     * @throws AuthException
     * @return Auth
     */
    public function getAuth()
    {
        if (!$this->auth) {
            throw new AuthException('Auth instance not found in Auth Adapter');
        }
        return $this->auth;
    }


}
