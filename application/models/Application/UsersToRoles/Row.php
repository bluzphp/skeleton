<?php
/**
 * User
 *
 * @category Application
 * @package  Roles
 */
namespace Application\Roles;

/**
 *
 */
class Row extends \Bluz\Db\Row
{
    /**
     * @var integer
     */
    public $id;

    /**
     * @var integer
     */
    public $userId;

    /**
     * @var integer
     */
    public $roleId;
}
