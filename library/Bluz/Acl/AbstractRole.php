<?php
/**
 * Role
 *
 * @category Bluz
 * @package  Acl
 */
namespace Bluz\Acl;

abstract class AbstractRole extends \Bluz\Db\Row
{
    /**
     * @var array of Role's
     */
    protected $_parents;

    /**
     * @var array of rules
     */
    protected $_rules;

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
    abstract protected function _getParentRoles();

    /**
     * Get rules
     *
     * @return array
     */
    abstract protected function _getRules();


    /**
     * Is role basic
     *
     * @return boolen
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
        $this->_rules = null;
        $this->_parents = null;

        return $this;
    }

    /**
     * Get parent roles
     *
     * @return array of roles
     */
    public function getParents($inherited = false)
    {
        if (null === $this->_parents) {
            $this->_parents = array();

            foreach ($this->_getParentRoles() as $role) {
                $this->_parents[$role->getId()] = $role;
            }
        }

        $parents = $this->_parents;
        if ($inherited) {
            foreach ($parents as $parent) {
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
     * @param boolen $inherited
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
     * @param boolen $inherited
     * @return array
     */
    public function getRules($inherited = true)
    {
        //get role specific privileges
        if (null === $this->_rules) {
            $this->_rules = array();

            foreach ($this->_getRules() as $row) {
                $this->_rules[ $row->privilege ] = $row->flag;
            }
        }

        $rules = $this->_rules;

        //get inherited privileges
        if ($inherited) {
            foreach ($this->getParents() as $parent) {
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
     * @return boolen
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
     * @param boolen $inherited
     * @return string
     */
    public function getPrivilegesAsString($inherited = true)
    {
        $privileges = "";
        foreach ($this->getPrivileges($inherited) as $privilege) {
            $privileges .= $privilege . ",";
        }
        return trim($privileges, ',');
    }
}
