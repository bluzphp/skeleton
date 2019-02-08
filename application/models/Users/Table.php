<?php
/**
 * @copyright Bluz PHP Team
 * @link      https://github.com/bluzphp/skeleton
 */

declare(strict_types=1);

namespace Application\Users;

/**
 * Users Table
 *
 * @package  Application\Users
 *
 * @method   static ?Row findRow($primaryKey)
 * @method   static ?Row findRowWhere($whereList)
 *
 * @author   Anton Shevchuk
 * @created  08.07.11 17:36
 */
class Table extends \Bluz\Db\Table
{
    /**
     * Pending email verification
     */
    public const STATUS_PENDING = 'pending';
    /**
     * Active user
     */
    public const STATUS_ACTIVE = 'active';
    /**
     * Disabled by administrator
     */
    public const STATUS_DISABLED = 'disabled';
    /**
     * Removed account
     */
    public const STATUS_DELETED = 'deleted';
    /**
     * system user with ID=1
     */
    public const SYSTEM_USER = 1;

    /**
     * Table
     *
     * @var string
     */
    protected $name = 'users';

    /**
     * Primary key(s)
     *
     * @var array
     */
    protected $primary = ['id'];

    /**
     * Init table relations
     *
     * @return void
     */
    public function init(): void
    {
        $this->linkTo('id', 'UsersRoles', 'userId');
        $this->linkToMany('Roles', 'UsersRoles');
    }
}
