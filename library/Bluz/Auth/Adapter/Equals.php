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

    /**
     * @var string
     */
    protected $credentialColumn;

    /**
     * @var closure
     */
    protected $encryptFunction;

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
        $password = call_user_func($this->encryptFunction, $password);
        $credential = $entity->getTable()->getCredentialColumn();

        return $entity->isEqual($credential, $password);
    }

    /**
     * set column name
     *
     * @param string $name
     * @return AbstractAdapter
     */
    public function setCredentialColumn($name)
    {
        $this->credentialColumn = $name;
        return $this;
    }

    /**
     * setEncryptFunction
     *
     * @param \closure $function
     * @return Equals
     */
    public function setEncryptFunction($function)
    {
        $this->encryptFunction = $function;
        return $this;
    }

}
