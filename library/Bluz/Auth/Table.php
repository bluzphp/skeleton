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
    protected $identityColumn;

    /**
     * @var string
     */
    protected $credentialColumn;

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

        $query = $this->select . " WHERE {$this->identityColumn} = ?";
        $user = $this->fetch($query, array($username))->current();

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
        return $this->identityColumn;
    }

    /**
     * Get Credential Column
     *
     * @return string
     */
    public function getCredentialColumn()
    {
        return $this->credentialColumn;
    }

    /**
     * Get auth
     *
     * @return Auth
     */
    public function getAuth()
    {
        return \Bluz\Application::getInstance()->getAuth();
    }
}
