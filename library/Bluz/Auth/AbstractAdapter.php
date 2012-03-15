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
use Bluz\Options;
abstract class AbstractAdapter
{
    /**
     * @var Auth
     */
    protected $_auth;

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
     * Constructor
     *
     * @param array $options
     * @access  public
     */
    public function __construct($options = null)
    {
        Options::setConstructorOptions($this, $options);
    }

    /**
     * Setup options
     * @param array $options
     */
    public function setOptions(array $options)
    {
        Options::setOptions($this, $options);
    }

    /**
     * setAuth
     *
     * @param $auth
     * @return Table
     */
    public function setAuth($auth)
    {
        $this->_auth = $auth;
        return $this;
    }

    /**
     * getAuth
     *
     * @return Auth
     */
    public function getAuth()
    {
        if (!$this->_auth) {
            throw new AuthException('Auth instance not found in Auth Adapter');
        }
        return $this->_auth;
    }
}
