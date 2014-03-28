<?php
/**
 * @namespace
 */
namespace Application\Tests\Fixtures\Users;

use Application\Users\Row;

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
     * Check user role
     *
     * @param integer $roleId
     * @return boolean
     */
    public function hasRole($roleId)
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
    public function hasPrivilege($module, $privilege)
    {
        return true;
    }
}
