<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\UsersActions;

use Bluz\Proxy\Db;

/**
 * Table
 *
 * @package  Application\Users
 *
 * @method   static Row findRow($primaryKey)
 * @method   static Row findRowWhere($whereList)
 *
 * @author   Anton Shevchuk
 * @created  08.07.11 17:36
 */
class Table extends \Bluz\Db\Table
{
    const ACTION_ACTIVATION = 'activation';
    const ACTION_CHANGE_EMAIL = 'email';
    const ACTION_RECOVERY = 'recovery';
    const ACTION_REMOVE = 'remove';

    /**
     * Table
     *
     * @var string
     */
    protected $table = 'users_actions';

    /**
     * Primary key(s)
     * @var array
     */
    protected $primary = array('userId', 'code');

    /**
     * generate action with token
     *
     * @param int $userId
     * @param string $action
     * @param int $expired in days
     * @param array $params
     * @return Row
     */
    public function generate($userId, $action, $expired = 5, $params = [])
    {
        // remove previously generated tokens
        Db::delete($this->table)
            ->where('userId = ?', $userId)
            ->andWhere('action = ?', $action)
            ->execute();

        // create new row
        $actionRow = new Row();
        $actionRow->userId = $userId;
        $actionRow->action = $action;
        $random = range('a', 'z', rand(1, 5));
        shuffle($random);
        $actionRow->code = md5($userId . $action . join('', $random) . time());
        $actionRow->expired = date('Y-m-d H:i:s', strtotime("+$expired day"));
        $actionRow->params = $params;
        $actionRow->save();

        return $actionRow;
    }
}
