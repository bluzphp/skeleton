<?php
/**
 * Ldap
 *
 * @category Bluz
 * @package  Auth
 *
 * @author   Anton Shevchuk
 * @author
 * @created  27.09.11 13:28
 */
namespace Bluz\Auth\Adapter;

use Bluz\Auth\AbstractAdapter;

class Equals extends AbstractAdapter
{
    protected $_credentialColumn;

    protected $_encryptFunction;

    /**
     * authenticate
     *
     * @param string $login
     * @param string $password
     * @param \Bluz\Auth\AbstractEntity $entity
     * @return bool
     */
    public function authenticate($login, $password, \Bluz\Auth\AbstractEntity $entity = null)
    {
        $password = call_user_func($this->_encryptFunction, $password);
        $credential = $entity->getTable()->getCredentialColumn();

        return $entity->isEqual($credential, $password);
    }

    /**
     * setEncryptFunction
     *
     * @param closure $function
     * @return Equals
     */
    public function setEncryptFunction($function)
    {
        $this->_encryptFunction = $function;
        return $this;
    }

    /**
     * setHost
     *
     * @param string $name
     * @return Equals
     */
    public function setCredentialColumn($name)
    {
        $this->_credentialColumn = $name;
        return $this;
    }
}
