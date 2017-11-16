<?php
/**
 * @namespace
 */
namespace Application\Tests\Fixtures\Users;

use Application\Users\Row;
use Application\Users\Table;

/**
 * Row
 *
 * @package  Application\Tests
 *
 * @author   Anton Shevchuk
 * @created  28.03.14 18:25
 */
class UserHasPermission extends Row
{
    /**
     * getId
     *
     * @return int
     */
    public function getId() : int
    {
        return Table::SYSTEM_USER;
    }

    /**
     * Check user role
     *
     * @param integer $roleId
     * @return boolean
     */
    public function hasRole($roleId) : bool
    {
        return true;
    }

    /**
     * Has role a privilege
     *
     * @param string $module
     * @param string $privilege
     * @return boolean
     */
    public function hasPrivilege($module, $privilege) : bool
    {
        return true;
    }
}
