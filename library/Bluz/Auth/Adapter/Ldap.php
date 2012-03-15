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

class Ldap extends AbstractAdapter
{
    /**
     * @var string
     */
    protected $_host = '';

    /**
     * @var string
     */
    protected $_domain = '';

    /**
     * @var string
     */
    protected $_baseDn = '';

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
        $result = null;
        /*$ldap = $this->getAuth()
            ->getApplication()
            ->getLdap();*/
        $ldap = new \Bluz\Ldap\Ldap();

        $ldap->initConnector($this->_host, $this->_domain, $this->_baseDn);

        return $ldap->checkAuth($login, $password);
    }

    /**
     * setHost
     *
     * @param string $name
     * @return Table
     */
    public function setHost($name)
    {
        $this->_host = $name;
        return $this;
    }

    /**
     * setDomain
     *
     * @param string $name
     * @return Table
     */
    public function setDomain($name)
    {
        $this->_domain = $name;
        return $this;
    }

    /**
     * setBaseDn
     *
     * @param string $name
     * @return Ldap
     */
    public function setBaseDn($name)
    {
        $this->_baseDn = $name;
        return $this;
    }


    /**
     * setIdentityColumn
     *
     * @param string $name
     * @return Ldap
     */
    public function setIdentityColumn($name)
    {
        $this->_identityColumn = $name;
        return $this;
    }
}
