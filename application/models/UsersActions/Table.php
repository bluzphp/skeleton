<?php

/**
 * @copyright Bluz PHP Team
 * @link      https://github.com/bluzphp/skeleton
 */

declare(strict_types=1);

namespace Application\UsersActions;

use Bluz\Proxy\Db;

/**
 * Table of User Actions
 *
 * @package  Application\Users
 *
 * @method   static Row|null findRow($primaryKey)
 * @see      \Bluz\Db\Table::findRow()
 * @method   static Row|null findRowWhere($whereList)
 * @see      \Bluz\Db\Table::findRowWhere()
 */
class Table extends \Bluz\Db\Table
{
    public const ACTION_ACTIVATION = 'activation';
    public const ACTION_CHANGE_EMAIL = 'email';
    public const ACTION_RECOVERY = 'recovery';
    public const ACTION_REMOVE = 'remove';

    /**
     * Table
     *
     * @var string
     */
    protected $name = 'users_actions';

    /**
     * Primary key(s)
     *
     * @var array
     */
    protected $primary = ['userId', 'code'];

    /**
     * generate action with token
     *
     * @param int $userId
     * @param string $action
     * @param int $expired in days
     * @param array $params
     *
     * @return Row
     * @throws \Bluz\Db\Exception\DbException
     * @throws \Bluz\Db\Exception\InvalidPrimaryKeyException
     * @throws \Bluz\Db\Exception\TableNotFoundException
     */
    public function generate($userId, $action, $expired = 5, $params = []): Row
    {
        // remove previously generated tokens
        Db::delete($this->name)
            ->where('userId = ?', $userId)
            ->andWhere('action = ?', $action)
            ->execute();

        // create new row
        $actionRow = new Row();
        $actionRow->userId = $userId;
        $actionRow->action = $action;
        $actionRow->code = bin2hex(random_bytes(32));
        $actionRow->expired = gmdate('Y-m-d H:i:s', strtotime("+$expired day"));
        $actionRow->params = $params;
        $actionRow->save();

        return $actionRow;
    }
}
