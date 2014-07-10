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
