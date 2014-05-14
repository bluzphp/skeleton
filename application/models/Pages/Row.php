<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Pages;

use Application\Users;

/**
 * Pages Row
 *
 * @property integer $id
 * @property string $title
 * @property string $alias
 * @property string $content
 * @property string $keywords
 * @property string $description
 * @property string $created
 * @property string $updated
 * @property integer $userId
 *
 * @category Application
 * @package  Application\Pages
 */
class Row extends \Bluz\Db\Row
{
    /**
     * __insert
     *
     * @return void
     */
    public function beforeInsert()
    {
        $this->created = gmdate('Y-m-d H:i:s');
        /* @var \Application\Users\Row $user */
        if ($user = app()->user()) {
            $this->userId = $user->id;
        } else {
            $this->userId = Users\Table::SYSTEM_USER;
        }
    }

    /**
     * __update
     *
     * @return void
     */
    public function beforeUpdate()
    {
        $this->updated = gmdate('Y-m-d H:i:s');
    }
}
