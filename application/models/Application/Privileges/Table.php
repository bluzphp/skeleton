<?php
/**
 * Copyright (c) 2013 by Bluz PHP Team
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * @namespace
 */
namespace Application\Privileges;

use Bluz\Cache\Cache;
use Application\Roles;

/**
 * Table
 *
 * @category Application
 * @package  Privileges
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
        if (!$data = app()->getCache()->get($cacheKey)) {
            $data = Db::getDefaultAdapter()->fetchColumn(
                "SELECT DISTINCT r.id, CONCAT(p.module, ':', p.privilege)
                FROM acl_privileges AS p, acl_roles AS r, acl_users_roles AS u2r
                WHERE p.roleId = r.id AND r.id = u2r.roleId AND u2r.userId = ?
                ORDER BY module, privilege",
                array((int) $userId)
            );

            app()->getCache()->set($cacheKey, $data, Cache::TTL_NO_EXPIRY);
            app()->getCache()->addTag($cacheKey, 'privileges');
            app()->getCache()->addTag($cacheKey, 'user:'.$userId);
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

        if (!$data = app()->getCache()->get($cacheKey)) {
            $data = app()->getDb()->fetchColumn(
                "SELECT DISTINCT CONCAT(p.module, ':', p.privilege)
                FROM acl_privileges AS p, acl_roles AS r
                WHERE p.roleId = r.id AND r.id = ?
                ORDER BY module, privilege",
                array((int) $roleId)
            );

            app()->getCache()->set($cacheKey, $data, Cache::TTL_NO_EXPIRY);
            app()->getCache()->addTag($cacheKey, 'privileges');
        }
        return $data;
    }
}
