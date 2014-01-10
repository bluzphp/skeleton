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
 * @category Application
 * @package  Roles
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
}
