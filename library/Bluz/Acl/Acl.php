<?php

/**
 * Copyright (c) 2011 by Bluz PHP Team
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
namespace Bluz\Acl;

use Bluz\Package;

/**
 * Acl
 *
 * @category Bluz
 * @package  Acl
 *
 * @author   Anton Shevchuk
 * @created  11.07.11 15:09
 */
class Acl extends Package
{
    const ALLOW = 'allow';
    const DENY = 'deny';

    /**
     * @var string
     */
    protected $_identityField;

    /**
     * Roles graph
     *
     * @var array
     */
    protected $_roles = array();

    /**
     * Acl actions
     *
     * @var array
     */
    protected $_actions = array();

    /**
     * Acl resources
     *
     * @var array
     */
    protected $_resources = array();

    /**
     * Acl links
     *
     * @var array
     */
    protected $_links = array();

    /**
     * @var Role
     */
    protected $_graph = null;

    /**
     * setIdentityField
     *
     * @param string $fieldName
     * @return Acl
     */
    public function setIdentityField($fieldName)
    {
        $this->_identityField = $fieldName;
        return $this;
    }

    /**
     * process
     *
     * @return Acl
     */
    public function process()
    {
        // build graph array with id's
        foreach ($this->_roles as $sid => $role) {
            $this->_graph[$sid] = new Role();
            $children = array_filter($this->_links, function($var) use ($sid) {
                    return in_array($sid, $var);
                });
            $this->_graph[$sid]->setChildren(array_keys($children));
            if (isset($this->_links[$sid])) {
                $this->_graph[$sid]->setParents($this->_links[$sid]);
            }

            if (isset($this->_actions[$sid])) {
                $actions = array();
                foreach ($this->_actions[$sid] as $resource) {
                    $actions[$resource['action']] = $resource;
                }
                $this->_graph[$sid]->setActions($actions);
            }
        }

        // rebuild with objects
        foreach ($this->_graph as $role) {
            /* @var Role $role */
            $children = $role->getChildren();
            if (sizeof($children)) {
                $childrenObj = array();
                foreach ($children as $childrenId) {
                    if (isset($this->_graph[$childrenId])) {
                        $childrenObj[] = $this->_graph[$childrenId];
                    }
                }
                $role->setChildren($childrenObj);
            }

            $parents = $role->getParents();
            if (sizeof($parents)) {
                $parentsObj = array();
                foreach ($parents as $parentId) {
                    if (isset($this->_graph[$parentId])) {
                        $parentsObj[] = $this->_graph[$parentId];
                    }
                }
                $role->setParents($parentsObj);
            }
        }
    }

    /**
     * __wakeup
     *
     * @return void
     */
    public function __wakeup()
    {
        //$this->process();
    }

    /**
     * __sleep
     *
     * @return array
     */
    public function __sleep()
    {
         return array('_roles', '_actions', '_resources', '_links', '_graph');
    }

    /**
     * setRoles
     *
     * @param array $roles
     * @return Acl
     */
    public function setRoles(array $roles)
    {
        $this->_roles = $roles;
        return $this;
    }

    /**
     * setActions
     *
     * @param array $actions
     * @return Acl
     */
    public function setActions($actions)
    {
        if (!is_array($actions)) {
            throw new AclException('Rules data should be array');
        }
        $this->_actions = $actions;
        return $this;
    }

    /**
     * setup links role to role
     *
     * @param array $links
     * @return Acl
     */
    public function setLinks($links)
    {
        if (!is_array($links)) {
            throw new AclException('Rules data should be array');
        }
        $this->_links = $links;
        return $this;
    }

    /**
     * isAllowed
     *
     * @param string $key of resource
     * @param integer $sid role UID of current user
     * @return boolean
     */
    public function isAllowed($key, $sid = 0)
    {
        if (!$sid) {
            if ($identity = $this->getApplication()->getAuth()->getIdentity()) {
                $sid = $identity->getRoleId();
            }
        }

        if (!isset($this->_graph[$sid])) {
            //throw new AclException('Role Id not found');
            return false;
        }
        return ($this->_graph[$sid]->isAllowed($key) == self::ALLOW);
    }
}
