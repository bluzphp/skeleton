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
    protected $host = '';

    /**
     * @var string
     */
    protected $domain = '';

    /**
     * @var string
     */
    protected $baseDn = '';

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
        /*$result = null;
        $ldap = $this->getAuth()
            ->getApplication()
            ->getLdap();*/
        $ldap = new \Bluz\Ldap\Ldap();

        $ldap->initConnector($this->host, $this->domain, $this->baseDn);

        return $ldap->checkAuth($login, $password);
    }

    /**
     * setHost
     *
     * @param string $name
     * @return Ldap
     */
    public function setHost($name)
    {
        $this->host = $name;
        return $this;
    }

    /**
     * setDomain
     *
     * @param string $name
     * @return Ldap
     */
    public function setDomain($name)
    {
        $this->domain = $name;
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
        $this->baseDn = $name;
        return $this;
    }
}
