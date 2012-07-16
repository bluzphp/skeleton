<?php
/**
 * Table
 *
 * @category Application
 * @package  Roles
 */
namespace Application\UsersToRoles;

use Bluz\Exception;

class Table extends \Bluz\Db\Table
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'acl_usersToRoles';

    /**
     * Primary key(s)
     * @var array
     */
    protected $primary = array('id');

}