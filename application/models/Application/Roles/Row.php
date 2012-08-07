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
        return in_array(strtolower($this->name), ['guest', 'administrator']);
    }
}
