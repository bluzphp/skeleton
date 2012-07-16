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
     * Get privileges
     */
    public function getPrivileges()
    {
        return $this->getApplication()->getCache()->getData('privileges:'.$this->id, function() {
            return $this->getTable()->getAdapter()->fetchColumnGroup("
                        SELECT DISTINCT p.module, p.privilege
                        FROM acl_privileges AS p, acl_roles AS r, acl_usersToRoles AS u2r
                        WHERE p.roleId = r.id AND r.id = u2r.roleId AND u2r.userId = ?
                        ORDER BY module, privilege",
                        array($this->id)
                    );
        }, 0);
    }
}
