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
namespace Application\Users;

use Application\Exception;
use Application\Privileges;
use Application\Roles;
use Bluz\Auth\AbstractRowEntity;
use Bluz\Auth\AuthException;

/**
 * User
 *
 * @category Application
 * @package  Users
 *
 * @property integer $id
 * @property string $login
 * @property string $email
 * @property string $created
 * @property string $updated
 * @property string $status
 *
 * @author   Anton Shevchuk
 * @created  08.07.11 17:13
 */
class Row extends AbstractRowEntity
{
    /**
     * Small cache of user privileges
     * @var array
     */
    protected $privileges;

    /**
     * @return void
     */
    public function beforeInsert()
    {
        $this->email = strtolower($this->email);
        $this->created = gmdate('Y-m-d H:i:s');
    }

    /**
     * @return void
     */
    public function beforeUpdate()
    {
        $this->email = strtolower($this->email);
        $this->updated = gmdate('Y-m-d H:i:s');
    }

    /**
     * Can entity login
     *
     * @throws Exception
     * @throws AuthException
     * @return boolean
     */
    public function tryLogin()
    {
        switch ($this->status) {
            case (Table::STATUS_PENDING):
                throw new AuthException("Your account is pending activation");
                break;
            case (Table::STATUS_DISABLED):
                throw new AuthException("Your account is disabled by administrator");
                break;
            case (Table::STATUS_ACTIVE):
                // all ok
                break;
            default:
                throw new Exception("User status is undefined in system");
                break;
        }
    }

    /**
     * Get user roles
     */
    public function getRoles()
    {
        return Roles\Table::getInstance()->getUserRoles($this->id);
    }

    /**
     * Get user privileges
     */
    public function getPrivileges()
    {
        if (!$this->privileges) {
            $this->privileges = Privileges\Table::getInstance()->getUserPrivileges($this->id);
        }
        return $this->privileges;
    }

    /**
     * Check user role
     *
     * @param integer $roleId
     * @return boolean
     */
    public function hasRole($roleId)
    {
        $roles = Roles\Table::getInstance()->getUserRolesIdentity($this->id);

        return in_array($roleId, $roles);
    }
}
