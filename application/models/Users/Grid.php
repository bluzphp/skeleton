<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Users;

/**
 * @category Application
 * @package  Users
 */
class Grid extends \Bluz\Grid\Grid
{
    protected $uid = 'users';

    /**
     * init
     * 
     * @return self
     */
    public function init()
    {
        // Array
        $adapter = new \Bluz\Grid\Source\SelectSource();


        $select = new \Bluz\Db\Query\Select();
        $select->select('u.*, GROUP_CONCAT( ar.`name` SEPARATOR ", " ) AS rolesList')
            ->from('users', 'u')
            ->leftJoin('u', 'acl_users_roles', 'aur', 'u.`id` = aur.`userId`')
            ->leftJoin('aur', 'acl_roles', 'ar', 'ar.`id` = aur.`roleId`')
            ->groupBy('u.id');


        $adapter->setSource($select);

        $this->setAdapter($adapter);
        $this->setDefaultLimit(25);
        $this->setAllowOrders(['login', 'email', 'status', 'id']);
        $this->setAllowFilters(['login', 'email', 'status', 'id', 'roleId']);
        return $this;
    }
}
