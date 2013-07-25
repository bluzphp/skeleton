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

use Bluz\Db\Rowset;

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
     * @return \Bluz\Db\Rowset
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
     * @return \Bluz\Db\Rowset
     */
    public function getUserPrivileges($userId)
    {
        if (!$seralizedData = app()->getCache()->get('privileges:'.$userId)) {
            $data = $this->fetch(
                "SELECT DISTINCT p.roleId, p.module, p.privilege
                FROM acl_privileges AS p, acl_roles AS r, acl_users_roles AS u2r
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
