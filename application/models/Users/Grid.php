<?php
/**
 * @copyright Bluz PHP Team
 * @link      https://github.com/bluzphp/skeleton
 */

declare(strict_types=1);

namespace Application\Users;

use Bluz\Db\Query\Select;
use Bluz\Grid\Source\SelectSource;

/**
 * Grid of users
 *
 * @method   Row[] getData()
 *
 * @package  Application\Users
 */
class Grid extends \Bluz\Grid\Grid
{
    /**
     * @var string
     */
    protected $uid = 'users';

    /**
     * {@inheritdoc}
     * @throws \Bluz\Grid\GridException
     */
    public function init(): void
    {
        // Create Select
        $select = Table::select();
        $select->select('users.*, GROUP_CONCAT( ar.`name` SEPARATOR ", " ) AS rolesList')
            ->leftJoin('users', 'acl_users_roles', 'aur', 'users.`id` = aur.`userId`')
            ->leftJoin('aur', 'acl_roles', 'ar', 'ar.`id` = aur.`roleId`')
            ->groupBy('users.id');


        // Setup adapter
        $adapter = new SelectSource();
        $adapter->setSource($select);

        $this->addAlias('users.login', 'login');
        $this->addAlias('users.email', 'email');
        $this->addAlias('users.status', 'status');
        $this->addAlias('users.id', 'id');
        $this->addAlias('users.roleId', 'role');

        $this->setAdapter($adapter);
        $this->setDefaultLimit(25);
        $this->setAllowOrders(['login', 'email', 'status', 'id']);
        $this->setAllowFilters(['login', 'email', 'status', 'id', 'roleId']);
    }
}
