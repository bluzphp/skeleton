<?php
/**
 * Table
 *
 * @category Application
 * @package  Privileges
 */
namespace Application\Privileges;

use Bluz\Db\Rowset;

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
        if (!$seralizedData = app()->getCache()->get('privileges:'.$userId)) {
            $data = $this->fetch("
                        SELECT DISTINCT p.roleId, p.module, p.privilege
                        FROM acl_privileges AS p, acl_roles AS r, acl_usersToRoles AS u2r
                        WHERE p.roleId = r.id AND r.id = u2r.roleId AND u2r.userId = ?
                        ORDER BY module, privilege",
                array((int) $userId)
            );

            app()->getCache()->set('privileges:'.$userId, $data->serialize(), 0);
            app()->getCache()->addTag('privileges:'.$userId, 'privileges');
            app()->getCache()->addTag('privileges:'.$userId, 'user:'.$userId);
        } else {
            $data = new Rowset();
            $data->unserialize($seralizedData);
        }
        return $data;
    }
}