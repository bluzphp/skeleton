<?php
/**
 * Table
 *
 * @category Application
 * @package  Roles
 */
namespace Application\Roles;

use Bluz\Exception;

class Table extends \Bluz\Db\Table
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'acl_roles';

    /**
     * Primary key(s)
     * @var array
     */
    protected $primary = array('id');

    /**
     * Get all roles in system
     *
     * @return \Bluz\Db\Rowset
     */
    public function getRoles()
    {
        return $this->fetch("SELECT * FROM acl_roles ORDER BY id");
    }

    /**
     * Get all user roles in system
     *
     * @param integer $userId
     * @return \Bluz\Db\Rowset
     */
    public function getUserRoles($userId)
    {
        return $this->fetch("
            SELECT r.*
            FROM acl_roles AS r, acl_usersToRoles AS u2r
            WHERE r.id = u2r.roleId AND u2r.userId = ?
            ", array($userId));
    }


}