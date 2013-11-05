<?php
/**
 * User
 *
 * @category Application
 * @package  Roles
 */
namespace Application\Roles;

/**
 * Class Row
 * @package Application\Roles
 *
 * @property integer $id
 * @property string $name
 */
class Row extends \Bluz\Db\Row
{
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
