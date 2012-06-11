<?php
/**
 * Table
 *
 * @category Application
 * @package  Users
 *
 * @author   Anton Shevchuk
 * @created  08.07.11 17:36
 */
namespace Application\Users;
class Table extends \Bluz\Auth\Table
{
    /**
     * @var Table
     */
    static $_instance;

    /**
     * Table
     *
     * @var string
     */
    protected $table = 'users';

    protected $identityColumn = 'login';

    protected $credentialColumn = 'password';

    protected $rowClass = '\Application\Users\Row';

    /**
     * Primary key(s)
     * @var array
     */
    protected $primary = array('id');
}