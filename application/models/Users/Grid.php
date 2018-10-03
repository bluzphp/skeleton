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
        $select->select('u.*, GROUP_CONCAT( ar.`name` SEPARATOR ", " ) AS rolesList')
            ->from('users', 'u')
            ->leftJoin('u', 'acl_users_roles', 'aur', 'u.`id` = aur.`userId`')
            ->leftJoin('aur', 'acl_roles', 'ar', 'ar.`id` = aur.`roleId`')
            ->groupBy('u.id');


        // Setup adapter
        $adapter = new SelectSource();
        $adapter->setSource($select);

        $this->setAdapter($adapter);
        $this->setDefaultLimit(25);
        $this->setAllowOrders(['login', 'email', 'status', 'id']);
        $this->setAllowFilters(['login', 'email', 'status', 'id', 'roleId']);
    }
}
