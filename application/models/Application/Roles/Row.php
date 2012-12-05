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
    const BASIC_GUEST = 'guest';
    const BASIC_MEMBER = 'member';
    const BASIC_ADMIN = 'admin';

    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * isBasic
     *
     * @return boolean
     */
    public function isBasic()
    {
        return in_array(strtolower($this->name), Table::getInstance()->getBasicRoles());
    }
}
