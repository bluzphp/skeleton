<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Categories;

/**
 * Class Row
 * @package Application\Categories
 *
 *
 * @property integer $id
 * @property integer $parentId
 * @property string $name
 * @property string $alias
 * @property string $created
 * @property string $updated
 * @property integer $order
 */
class Row extends \Bluz\Db\Row
{
    /**
     * @return void
     */
    public function beforeInsert()
    {
        $this->created = gmdate('Y-m-d H:i:s');
        if ($this->parentId == '') {
            $this->parentId = null;
        }
    }

    /**
     * @return void
     */
    public function beforeUpdate()
    {
        $this->updated = gmdate('Y-m-d H:i:s');
    }
}
