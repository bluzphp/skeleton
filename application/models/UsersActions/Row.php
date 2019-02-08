<?php
/**
 * @copyright Bluz PHP Team
 * @link      https://github.com/bluzphp/skeleton
 */

declare(strict_types=1);

namespace Application\UsersActions;

/**
 * Row of User Actions
 *
 * @package  Application\Users
 *
 * @property integer $userId
 * @property string  $code
 * @property string  $action
 * @property string  $params
 * @property string  $created
 * @property string  $expired
 */
class Row extends \Bluz\Db\Row
{
    /**
     * beforeSave
     *
     * @return void
     */
    public function beforeSave(): void
    {
        $this->params = serialize($this->params);
    }

    /**
     * Return params of token
     *
     * @return array
     */
    public function getParams(): array
    {
        return $this->params ? unserialize($this->params, ['allowed_classes' => false]): [];
    }
}
