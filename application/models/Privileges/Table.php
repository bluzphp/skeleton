<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Privileges;

use Application\Roles;
use Bluz\Proxy\Cache;
use Bluz\Proxy\Db;

/**
 * Table
 *
 * @package  Application\Privileges
 *
 * @method   static Row findRow($primaryKey)
 * @method   static Row findRowWhere($whereList)
 */
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
     * @return array
     */
    public function getPrivileges()
    {
        return $this->fetch(
            "SELECT DISTINCT p.roleId, p.module, p.privilege
            FROM acl_privileges AS p
            ORDER BY module, privilege"
        );
    }

    /**
     * Get user privileges
     *
     * @param integer $userId
     * @return array
     */
    public function getUserPrivileges($userId)
    {
        $roles = Roles\Table::getInstance()->getUserRolesIdentity($userId);

        $stack = [];
        foreach ($roles as $roleId) {
            $stack = array_merge($stack, $this->getRolePrivileges($roleId));
        }

        // magic array_unique for multi array
        return array_unique($stack);

        // follow code is faster, but required record for every user in memcache
        // in other words, need more memory for decrease CPU load
        // for update
        /*
        $cacheKey = 'privileges:user:'.$userId;
        if (!$data = Cache::get($cacheKey)) {
            $data = Db::getDefaultAdapter()->fetchColumn(
                "SELECT DISTINCT r.id, CONCAT(p.module, ':', p.privilege)
                FROM acl_privileges AS p, acl_roles AS r, acl_users_roles AS u2r
                WHERE p.roleId = r.id AND r.id = u2r.roleId AND u2r.userId = ?
                ORDER BY module, privilege",
                array((int) $userId)
            );

            Cache::set($cacheKey, $data, Cache::TTL_NO_EXPIRY);
            Cache::addTag($cacheKey, 'privileges');
            Cache::addTag($cacheKey, 'user:'.$userId);
        }
        return $data;
        */
    }

    /**
     * Get user privileges
     *
     * @param integer $roleId
     * @return array
     */
    public function getRolePrivileges($roleId)
    {
        $cacheKey = 'privileges:role:'.$roleId;

        if (!$data = Cache::get($cacheKey)) {
            $data = Db::fetchColumn(
                "SELECT DISTINCT CONCAT(p.module, ':', p.privilege)
                FROM acl_privileges AS p, acl_roles AS r
                WHERE p.roleId = r.id AND r.id = ?
                ORDER BY CONCAT(p.module, ':', p.privilege)",
                array((int) $roleId)
            );

            Cache::set($cacheKey, $data, Cache::TTL_NO_EXPIRY);
            Cache::addTag($cacheKey, 'privileges');
        }
        return $data;
    }
}
