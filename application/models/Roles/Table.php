<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Roles;

use Bluz\Cache\Cache;
use Bluz\Db\Db;

/**
 * Class Table
 *
 * @package  Application\Roles
 *
 *
 * @method   static Row findRow($primaryKey)
 * @method   static Row findRowWhere($whereList)
 */
class Table extends \Bluz\Db\Table
{
    const BASIC_GUEST = 'guest';
    const BASIC_MEMBER = 'member';
    const BASIC_ADMIN = 'admin';
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
     * @var array
     */
    protected $basicRoles = ['guest', 'member', 'admin'];

    /**
     * Get all roles in system
     *
     * @return array
     */
    public function getRoles()
    {
        return $this->fetch("SELECT * FROM acl_roles ORDER BY id");
    }

    /**
     * Get all basic roles
     * 
     * @return array
     */
    public function getBasicRoles()
    {
        return $this->basicRoles;
    }

    /**
     * Get all user roles in system
     *
     * @param integer $userId
     * @return array of rows
     */
    public function getUserRoles($userId)
    {
        $data = $this->fetch(
            "SELECT r.*
            FROM acl_roles AS r, acl_users_roles AS u2r
            WHERE r.id = u2r.roleId AND u2r.userId = ?",
            array($userId)
        );
        return $data;
    }

    /**
     * Get all user roles in system
     *
     * @param integer $userId
     * @return array of identity
     */
    public function getUserRolesIdentity($userId)
    {
        $cacheKey = 'roles:user:'.$userId;
        if (!$data = app()->getCache()->get($cacheKey)) {
            $data = Db::getDefaultAdapter()->fetchColumn(
                "SELECT r.id
                FROM acl_roles AS r, acl_users_roles AS u2r
                WHERE r.id = u2r.roleId AND u2r.userId = ?
                ORDER BY r.id ASC",
                array($userId)
            );
            app()->getCache()->set($cacheKey, $data, Cache::TTL_NO_EXPIRY);
            app()->getCache()->addTag($cacheKey, 'roles');
            app()->getCache()->addTag($cacheKey, 'user:'.$userId);
        }
        return $data;
    }
}
