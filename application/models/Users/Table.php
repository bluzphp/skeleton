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
 * Table
 *
 * @category Application
 * @package  Users
 *
 * @method   static Row findRow($primaryKey)
 * @method   static Row findRowWhere($whereList)
 *
 * @author   Anton Shevchuk
 * @created  08.07.11 17:36
 */
class Table extends \Bluz\Db\Table
{
    /**
     * Pending email verification
     */
    const STATUS_PENDING = 'pending';
    /**
     * Active user
     */
    const STATUS_ACTIVE = 'active';
    /**
     * Disabled by administrator
     */
    const STATUS_DISABLED = 'disabled';
    /**
     * Removed account
     */
    const STATUS_DELETED = 'deleted';
    /**
     * system user with ID=0
     */
    const SYSTEM_USER = 0;

    /**
     * Table
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Primary key(s)
     * @var array
     */
    protected $primary = array('id');

    /**
     * Init table relations
     * @return void
     */
    public function init()
    {
        $this->linkTo('id', 'UsersRoles', 'userId');
        $this->linkToMany('Roles', 'UsersRoles');
    }
}
