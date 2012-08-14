<?php
/**
 * Table
 *
 * @category Application
 * @package  Privileges
 */
namespace Application\Privileges;

class Table extends \Bluz\Db\Table
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'acl_privileges';

    /**
     * Primary key(s)
     * @var array
     */
    protected $primary = array('roleId', 'module', 'privilege');

    /**
     * Get all privileges
     *
     * @return \Bluz\Db\Rowset
     */
    public function getPrivileges()
    {
        return $this->fetch("
            SELECT DISTINCT p.roleId, p.module, p.privilege
            FROM acl_privileges AS p
            ORDER BY module, privilege"
        );
    }

    /**
     * Get user privileges
     *
     * @param integer $userId
     * @return \Bluz\Db\Rowset
     */
    public function getUserPrivileges($userId)
    {
        return \Bluz\Application::getInstance()->getCache()->getData('privileges:'.$userId, function() use ($userId) {
            return $this->fetch("
                        SELECT DISTINCT p.roleId, p.module, p.privilege
                        FROM acl_privileges AS p, acl_roles AS r, acl_usersToRoles AS u2r
                        WHERE p.roleId = r.id AND r.id = u2r.roleId AND u2r.userId = ?
                        ORDER BY module, privilege",
                        array((int) $userId)
                    );
        }, 0);
    }
}