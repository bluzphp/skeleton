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
    private $arrayWithTree = [];

    /**
     * @param array $data
     * @return void
     */
    public function validateCreate($data)
    {
        $this->checkName($data);
        $this->checkAlias($data);

        $alias = isset($data['alias']) ? $data['alias'] : null;

        if ($this->getTable()->findRowWhere(['alias' => $alias])) {
            $this->addError("Category with alias " . esc($alias) . " already exists", 'alias');
        }

    }

    /**
     * @param mixed $id
     * @param array $data
     * @return void
     */
    public function validateUpdate($id, $data)
    {
        $this->checkName($data);
        $this->checkAlias($data);

        if ($this->getTable()->isAliasDuplicated($data)) {
            $this->addError("Category with alias " . esc($data['alias']) . " already exists", 'alias');
        }

    }

    /**
     * checkName
     *
     * @param array $data
     * @return void
     */
    protected function checkName($data)
    {
        $name = isset($data['name']) ? $data['name'] : null;
        if (empty($name)) {
            $this->addError('Name can\'t be empty', 'catalog');
        }
        if (strlen($name) > 128) {
            $this->addError('Name can\'t be bigger than 128 symbols', 'catalog');
        }
    }

    /**
     * @param array $data
     */
    protected function checkAlias($data)
    {
        $alias = isset($data['alias']) ? $data['alias'] : null;
        if (empty($alias)) {
            $this->addError('Alias can\'t be empty', 'alias');
        }
        if (strlen($alias) > 64) {
            $this->addError('Alias can\'t be bigger than 64 symbols', 'alias');
        }
        if (!preg_match('/^[a-zA-Z0-9-]+$/', $alias)) {
            $this->addError('Alias should contains only Latin characters, dots and dashes', 'alias');
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
