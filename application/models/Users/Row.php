<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Users;

use Application\Exception;
use Application\Privileges;
use Application\Roles;
use Bluz\Auth\AbstractRowEntity;
use Bluz\Auth\AuthException;
use Bluz\Proxy\Auth;
use Bluz\Proxy\Session;
use Bluz\Validator\Traits\Validator;
use Bluz\Validator\Validator as v;

/**
 * User
 *
 * @category Application
 * @package  Users
 *
 * @property string $login
 * @property string $email
 * @property string $created
 * @property string $updated
 * @property string $status
 *
 * @author   Anton Shevchuk
 * @created  08.07.11 17:13
 */
class Row extends AbstractRowEntity
{
    use Validator;

    /**
     * Small cache of user privileges
     * @var array
     */
    protected $privileges;

    /**
     * @return void
     */
    public function beforeSave()
    {
        $this->email = strtolower($this->email);

        $this->addValidator(
            'login',
            v::required()->latin()->length(3, 255),
            v::callback(function ($login) {
                $user = $this->getTable()
                    ->select()
                    ->where('login = ?', $login)
                    ->andWhere('id != ?', $this->id)
                    ->execute();
                return !$user;
            })->setError('User with login "{{input}}" already exists')
        );

            $this->addValidator(
                'email',
                v::required()->email(true),
                v::callback(function ($email) {
                    $user = $this->getTable()
                    ->select()
                    ->where('email = ?', $email)
                    ->andWhere('id != ?', $this->id)
                    ->execute();
                    return !$user;
                })->setError('User with email "{{input}}" already exists')
            );
    }

    /**
     * @return void
     */
    public function beforeInsert()
    {
        $this->created = gmdate('Y-m-d H:i:s');
    }

    /**
     * @return void
     */
    public function beforeUpdate()
    {
        $this->updated = gmdate('Y-m-d H:i:s');
    }

    /**
     * Can entity login
     *
     * @throws Exception
     * @throws AuthException
     * @return void
     */
    public function tryLogin()
    {
        switch ($this->status) {
            case (Table::STATUS_PENDING):
                throw new AuthException("Your account is pending activation", 403);
            case (Table::STATUS_DISABLED):
                throw new AuthException("Your account is disabled by administrator", 403);
            case (Table::STATUS_ACTIVE):
                // all ok
                // regenerate session
                if (PHP_SAPI !== 'cli') {
                    Session::regenerateId();
                }
                // save user to new session
                Auth::setIdentity($this);
                break;
            default:
                throw new Exception("User status is undefined in system");
        }
    }

    /**
     * Get user roles
     */
    public function getRoles()
    {
        return Roles\Table::getInstance()->getUserRoles($this->id);
    }

    /**
     * Get user privileges
     */
    public function getPrivileges()
    {
        if (!$this->privileges) {
            $this->privileges = Privileges\Table::getInstance()->getUserPrivileges($this->id);
        }
        return $this->privileges;
    }

    /**
     * Check user role
     *
     * @param integer $roleId
     * @return boolean
     */
    public function hasRole($roleId)
    {
        $roles = Roles\Table::getInstance()->getUserRolesIdentity($this->id);

        return in_array($roleId, $roles);
    }
}
