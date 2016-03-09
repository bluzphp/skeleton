<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Users;

use Bluz\Db\Query\Select;
use Bluz\Grid\Source\SelectSource;

/**
 * @category Application
 * @package  Users
 */
class Grid extends \Bluz\Grid\Grid
{
    /**
     * @var string
     */
    protected $uid = 'users';

    /**
     * init
     *
     * @return self
     */
    public function init()
    {
        // Create Select
        $select = new Select();
        $select->select('u.*, u.id as uid, GROUP_CONCAT( ar.`name` SEPARATOR ", " ) AS rolesList')
            ->from('users', 'u')
            ->leftJoin('u', 'acl_users_roles', 'aur', 'u.`id` = aur.`userId`')
            ->leftJoin('aur', 'acl_roles', 'ar', 'ar.`id` = aur.`roleId`')
            ->groupBy('u.id');


        // Setup adapter
        $adapter = new SelectSource();
        $adapter->setSource($select);

        $this->setAdapter($adapter);
        $this->setDefaultLimit(25);
        $this->addAlias('id', 'u.id');
        $this->setAllowOrders(['login', 'email', 'status', 'id']);
        $this->setAllowFilters(['login', 'email', 'status', 'id', 'roleId']);
        return $this;
    }
}
