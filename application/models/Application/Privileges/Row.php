<?php
/**
 * User
 *
 * @category Application
 * @package  Privileges
 */
namespace Application\Privileges;

class Row extends \Bluz\Db\Row
{
    /**
     * @var integer
     */
    public $roleId;

    /**
     * @var string
     */
    public $module;

    /**
     * @var string
     */
    public $privilege;
}
