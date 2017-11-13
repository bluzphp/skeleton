<?php
/**
 * @copyright Bluz PHP Team
 * @link      https://github.com/bluzphp/skeleton
 */

declare(strict_types=1);

namespace Application\UsersRoles;

/**
 * Table
 *
 * @package  Application\Roles
 *
 * @method   static Row findRow($primaryKey)
 * @method   static Row findRowWhere($whereList)
 */
class Table extends \Bluz\Db\Table
{
    /**
     * Table
     *
     * @var string
     */
    protected $name = 'acl_users_roles';

    /**
     * Primary key(s)
     *
     * @var array
     */
    protected $primary = ['userId', 'roleId'];

    /**
     * init
     *
     * @return void
     */
    public function init()
    {
        $this->linkTo('userId', 'Users', 'id');
        $this->linkTo('roleId', 'Roles', 'id');
    }
}
