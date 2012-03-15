<?php
/**
 * User
 *
 * @category Application
 * @package  Users
 *
 * @author   Anton Shevchuk
 * @created  08.07.11 17:13
 */
namespace Application\Users;

/**
 * @property integer $id
 * @property string $login
 * @property string $created
 * @property string $updated
 */
class Row extends \Bluz\Auth\AbstractEntity
{
    /**
     * __insert
     *
     * @return void
     */
    public function _insert()
    {
        $this->created = gmdate('Y-m-d H:i:s');
    }

    /**
     * __update
     *
     * @return void
     */
    public function _update()
    {
        $this->updated = gmdate('Y-m-d H:i:s');
    }

    /**
     * Get role ID
     *
     * @return string
     */
    public function getRoleId()
    {
        return $this->id;
    }

    /**
     * Can entity login
     *
     * @return boolean
     */
    public function canLogin()
    {
        return 'active' == $this->status;
    }
}
