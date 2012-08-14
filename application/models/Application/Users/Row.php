<?php
/**
 * User
 *
 * @category Application
 * @package  Users
 *
 * @author   Anton Shevchuk
 * @created  08.07.11 17:13
 */
namespace Application\Users;

use Application\Roles;
use Application\Privileges;

/**
 * @property integer $id
 * @property string $login
 * @property string $created
 * @property string $updated
 */
class Row extends \Bluz\Auth\AbstractEntity
{
    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $login;

    /**
     * @var string
     */
    public $created;

    /**
     * @var string
     */
    public $updated;

    /**
     * @var string
     */
    public $status;

    /**
     * __insert
     *
     * @return void
     */
    public function preInsert()
    {
        $this->created = gmdate('Y-m-d H:i:s');
    }

    /**
     * __update
     *
     * @return void
     */
    public function preUpdate()
    {
        $this->updated = gmdate('Y-m-d H:i:s');
    }

    /**
     * Can entity login
     *
     * @return boolean
     */
    public function canLogin()
    {
        return 'active' == $this->status;
    }

    /**
     * Get roles
     */
    public function getRoles()
    {
        return Roles\Table::getInstance()->getUserRoles($this->id);
    }

    /**
     * Get privileges
     */
    public function getPrivileges()
    {
        return Privileges\Table::getInstance()->getUserPrivileges($this->id);
    }

    /**
     * hasRole
     *
     * @param $roleId
     * @return boolean
     */
    public function hasRole($roleId)
    {
        $roles = $this->getRoles();

        foreach ($roles as $role) {
            if (is_numeric($roleId)) {
                if ($role->id == $roleId) {
                    return true;
                }
            } else {
                if ($role->name == $roleId) {
                    return true;
                }
            }
        }

        return false;
    }
}
