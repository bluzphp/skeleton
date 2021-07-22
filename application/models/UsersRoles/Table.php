<?php

/**
 * @copyright Bluz PHP Team
 * @link      https://github.com/bluzphp/skeleton
 */

declare(strict_types=1);

namespace Application\UsersRoles;

/**
 * Table of Users to Roles relations
 *
 * @package  Application\Roles
 *
 * @method   static Row|null findRow($primaryKey)
 * @see      \Bluz\Db\Table::findRow()
 * @method   static Row|null findRowWhere($whereList)
 * @see      \Bluz\Db\Table::findRowWhere()
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
    public function init(): void
    {
        $this->linkTo('userId', 'Users', 'id');
        $this->linkTo('roleId', 'Roles', 'id');
    }
}
