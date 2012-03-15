<?php
/**
 * Role
 *
 * @category Bluz
 * @package  Acl
 *
 * @author   Anton Shevchuk
 * @created  18.07.11 17:44
 */
namespace Bluz\Acl;
class Role
{
    /**
     * Unique role ID
     * @var integer
     */
    protected $_sid;

    /**
     * @var array of Role's
     */
    protected $_parents = array();

    /**
     * @var array of Role's
     */
    protected $_children = array();

    /**
     * @var array of actions
     */
    protected $_actions = array();

    /**
     * getParent
     *
     * @return array of roles
     */
    public function getParents()
    {
        return $this->_parents;
    }

    /**
     * setParents
     *
     * @param array $parents
     * @return Role
     */
    public function setParents(array $parents)
    {
        $this->_parents = $parents;
        return $this;
    }

    /**
     * getChildren
     *
     * @return array of Role's
     */
    public function getChildren()
    {
        return $this->_children;
    }

    /**
     * setChildren
     *
     * @param array $children
     * @return Role
     */
    public function setChildren(array $children)
    {
        $this->_children = $children;
        return $this;
    }

    /**
     * getActions
     *
     * @return array
     */
    public function getActions()
    {
        return $this->_actions;
    }

    /**
     * setActions
     *
     * @param array $actions
     * @return Role
     */
    public function setActions(array $actions)
    {
        $this->_actions = $actions;
        return $this;
    }

    /**
     * isAllowed
     *
     * @param integer $actionId
     * @return string
     */
    public function isAllowed($actionId)
    {
        if (isset($this->_actions[$actionId])) {
            return $this->_actions[$actionId]['access'];
        } elseif (!sizeof($this->_parents)) {
            return \Bluz\Acl::DENY;
        } else {
            foreach ($this->_parents as $role) {
                if ($role->isAllowed($actionId) == \Bluz\Acl::ALLOW) {
                    return \Bluz\Acl::ALLOW;
                }
            }
            return \Bluz\Acl::DENY;
        }
    }
}
