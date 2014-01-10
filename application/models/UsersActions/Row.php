<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\UsersActions;

/**
 * User Actions
 *
 * @category Application
 * @package  Users
 *
 * @property integer $userId
 * @property string $code
 * @property string $action
 * @property string $params
 * @property string $created
 * @property string $expired
 *
 * @author   Anton Shevchuk
 * @created  05.12.12 10:32
 */
class Row extends \Bluz\Db\Row
{
    /**
     * beforeSave
     *
     * @return void
     */
    public function beforeSave()
    {
        $this->params = serialize($this->params);
    }

    /**
     * Return params of token
     *
     * @return array
     */
    public function getParams()
    {
        if ($this->params) {
            return unserialize($this->params);
        } else {
            return array();
        }
    }
}
