<?php

/**
 * Copyright (c) 2012 by Bluz PHP Team
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
 * FITNESS FOR A PARTICULAR PURPOSE AND NON INFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * @namespace
 */
namespace Bluz\Acl;

/**
 * Role
 *
 * @category Bluz
 * @package  Acl
 */
abstract class AbstractRole extends \Bluz\Db\Row
{
    /**
     * @var array of Role's
     */
    protected $parents;

    /**
     * @var array of rules
     */
    protected $rules;

    /**
     * Get name
     *
     * @return string
     */
    abstract function getName();

    /**
     * Get id
     *
     * @return integer
     */
    abstract function getId();

    /**
     * Get parent roles
     *
     * @return array|Rowset of Roles
     */
    abstract protected function getParentRoles();

    /**
     * Get rules
     *
     * @return array
     */
    abstract protected function getRoleRules();

    /**
     * @see Bluz\Db.Row::__sleep()
     * @return array
     */
    public function __sleep()
    {
        $return = parent::__sleep();
        $return[] = 'parents';
        $return[] = 'rules';

        return $return;
    }

    /**
     * Is role basic
     *
     * @return boolean
     */
    public function isBasic()
    {
        return ('god' == $this->getName() || 'guest' == $this->getName());
    }

    /**
     * Clear cache
     *
     * @return AbstractRole
     */
    public function clearCache()
    {
        $this->rules = null;
        $this->parents = null;

        return $this;
    }

    /**
     * Get parent roles
     *
     * @param bool $inherited
     * @return array of roles
     */
    public function getParents($inherited = false)
    {
        if (null === $this->parents) {
            $this->parents = array();

            foreach ($this->getParentRoles() as $role) {
                /* @var \Bluz\Acl\AbstractRole $role */
                $this->parents[$role->getId()] = $role;
            }
        }


        $parents = $this->parents;
        if ($inherited) {
            foreach ($parents as $parent) {
                /* @var AbstractRole $parent */
                foreach ($parent->getParents(true) as $pid => $pParent) {
                    if (!isset($parents[$pid])) {
                        $parents[$pid] = $pParent;
                    }
                }
            }
        }
        return $parents;
    }

    /**
     * Get privileges
     *
     * @param boolean $inherited
     * @return array
     */
    public function getPrivileges($inherited = true)
    {
        $rules = $this->getRules($inherited);

        //Remove denied privileges
        foreach ($rules as $privilege => $flag) {
            if ($flag !== Acl::ALLOW) {
                unset($rules[$privilege]);
            }
        }

        //return only allowed
        return array_keys($rules);
    }

    /**
     * Get rules
     *
     * @param boolean $inherited
     * @return array
     */
    public function getRules($inherited = true)
    {
        //get role specific privileges
        if (null === $this->rules) {
            $this->rules = array();

            foreach ($this->getRoleRules() as $row) {
                $this->rules[ $row->privilege ] = $row->flag;
            }
        }

        $rules = $this->rules;

        //get inherited privileges
        if ($inherited) {
            foreach ($this->getParents() as $parent) {
                /* @var AbstractRole $parent */
                foreach ($parent->getRules() as $parentPrivilege => $flag) {
                    if (!isset($rules[$parentPrivilege])) {
                        $rules[ $parentPrivilege ] = $flag;
                    }
                }
            }
        }

        return $rules;
    }

    /**
     * Has role a privilege
     *
     * @param string $privilege
     * @return boolean
     */
    public function hasPrivilege($privilege)
    {
        return in_array($privilege, $this->getPrivileges());
    }

    /**
     * To string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Get privileges as string
     *
     * @param boolean $inherited
     * @return string
     */
    public function getPrivilegesAsString($inherited = true)
    {
        $privileges = $this->getPrivileges($inherited);
        return join(', ', (array) $privileges);
    }
}
