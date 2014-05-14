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

/**
 * User
 *
 * @category Application
 * @package  Users
 *
 * @property integer $id
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
    /**
     * Small cache of user privileges
     * @var array
     */
    protected $privileges;

    /**
     * @return void
     */
    public function beforeInsert()
    {
        $this->email = strtolower($this->email);
        $this->created = gmdate('Y-m-d H:i:s');
    }

    /**
     * @return void
     */
    public function beforeUpdate()
    {
        $this->email = strtolower($this->email);
        $this->updated = gmdate('Y-m-d H:i:s');
    }

    /**
     * Can entity login
     *
     * @throws Exception
     * @throws AuthException
     * @return boolean|null
     */
    public function tryLogin()
    {
        switch ($this->status) {
            case (Table::STATUS_PENDING):
                throw new AuthException("Your account is pending activation");
            case (Table::STATUS_DISABLED):
                throw new AuthException("Your account is disabled by administrator");
            case (Table::STATUS_ACTIVE):
                // all ok
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
