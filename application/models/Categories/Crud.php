<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Categories;

use Bluz\Validator\Validator as v;
use Bluz\Validator\ValidatorBuilder;

/**
 * Class Crud
 * @package Application\Categories
 *
 * @method Table getTable()
 */
class Crud extends \Bluz\Crud\Table
{
    /**
     * @var array
     */
    private $arrayWithTree = [];

    /**
     * @param mixed $id
     * @param array $data
     * @return void
     */
    public function validate($id, $data)
    {
        $validator = new ValidatorBuilder();
        $validator->add(
            'name',
            v::required(),
            v::length(2, 128)
        );

        $validator->add(
            'alias',
            v::required(),
            v::length(2, 64),
            v::slug(),
            v::callback(function ($input) use ($data) {
                $select = $this->getTable()->select()
                    ->where('alias = ?', $input);

                if (isset($data['id'])) {
                    $select->andWhere('id != ?', $data['id']);
                }

                if (isset($data['parentId']) && !empty($data['parentId'])) {
                    $select->andWhere('parentId = ?', $data['parentId']);
                } else {
                    $select->andWhere('parentId IS NULL');
                }

                return !sizeof($select->execute());
            })->setError('Category with alias "{{input}}" already exists')
        );
        if (!$validator->validate($data)) {
            $this->setErrors($validator->getErrors());
        }
    }

    /**
     * @param mixed $data
     * @return int|void
     */
    public function deleteOne($data)
    {
        $table = Table::getInstance();
        $tree = $table->buildTree($data['id']);

        if (!isset($tree[0]['children'])) {
            parent::deleteOne($data);
        } else {
            $allSubCategories = $this->treeToArray($tree);

            foreach ($allSubCategories as $categoryId) {
                parent::deleteOne(['id' => $categoryId]);
            }
        }
    }

    /**
     * @param array $tree
     * @return array
     */
    private function treeToArray($tree)
    {
        foreach ($tree as $node) {
            if (empty($node['children'])) {
                $this->arrayWithTree[] = $node['id'];
            } else {
                $this->arrayWithTree[] = $node['id'];
                $this->treeToArray($node['children']);
            }
        }

        return $this->arrayWithTree;
    }
}
