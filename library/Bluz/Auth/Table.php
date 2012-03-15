<?php
/**
 * Table
 *
 * @category Bluz
 * @package  Auth
 *
 * @author   Anton Shevchuk
 * @created  12.07.11 15:28
 */
namespace Bluz\Auth;

class Table extends \Bluz\Db\Table
{
    /**
     * @var string
     */
    protected $_identityColumn;

    /**
     * @var string
     */
    protected $_credentialColumn;

    /**
     * Login
     *
     * @param string $username
     * @param string $password
     * @throws AuthException
     * @return AbstractEntity
     */
    public function login($username, $password)
    {
        if (empty($username)) {
            throw new AuthException("Login is empty");
        }

        if (empty($password)) {
            throw new AuthException("Password is empty");
        }

        $query = $this->_select . " WHERE {$this->_identityColumn} = ?";
        $user = $this->_fetch($query, array($username))->current();

        if (!$user) {
            throw new AuthException("User not found");
        }

        if (!$user->canLogin()) {
            throw new AuthException("There is a problem with your account");
        }

        if (!$this->getAuth()->authenticate($username, $password, $user)) {
            throw new AuthException("Error password");
        }
        return $user;

    }

    /**
     * Get Identity Column
     *
     * @return string
     */
    public function getIdentityColumn()
    {
        return $this->_identityColumn;
    }

    /**
     * Get Credential Column
     *
     * @return string
     */
    public function getCredentialColumn()
    {
        return $this->_credentialColumn;
    }

    /**
     * Get auth
     *
     * @return Auth
     */
    public function getAuth()
    {
        global $app;
        return $app->getAuth();
    }
}
