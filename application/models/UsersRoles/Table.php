<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
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
    protected $table = 'acl_users_roles';

    /**
     * Primary key(s)
     * @var array
     */
    protected $primary = array('userId', 'roleId');

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
