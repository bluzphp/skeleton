<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Categories;

use Bluz\Validator\Traits\Validator;
use Bluz\Validator\Validator as v;

/**
 * Class Row
 * @package Application\Categories
 *
 * @property integer $id
 * @property integer $rootId
 * @property integer $parentId
 * @property string $name
 * @property string $alias
 * @property string $created
 * @property string $updated
 */
class Row extends \Bluz\Db\Row
{
    use Validator;

    /**
     * @var Row[]
     */
    protected $children;

    /**
     * @return void
     */
    public function beforeSave()
    {
        $this->addValidator(
            'name',
            v::required(),
            v::length(2, 128)
        );

        $this->addValidator(
            'alias',
            v::required(),
            v::length(2, 64),
            v::slug(),
            v::callback(function ($input) {
                $select = $this->getTable()->select()
                    ->where('alias = ?', $input);

                if ($this->id) {
                    $select->andWhere('id != ?', $this->id);
                }

                if ($this->parentId) {
                    $select->andWhere('parentId = ?', $this->parentId);
                } else {
                    $select->andWhere('parentId IS NULL');
                }

                return !sizeof($select->execute());
            })->setError('Category with alias "{{input}}" already exists')
        );
    }

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

    /**
     * Add child
     *
     * @param Row $row
     */
    public function addChild(Row $row)
    {
        $this->children[$row->id] = $row;
    }

    /**
     * Get children directories
     *
     * @return array
     */
    public function getChildren()
    {
        return $this->children;
    }
}
