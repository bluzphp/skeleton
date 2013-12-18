<?php

/**
 * @namespace
 */
namespace Application\Categories;

use Application\Categories;

class Crud extends \Bluz\Crud\Table
{
    private $_arrayWithTree = [];

    /**
     * @param array $data
     * @return int
     */
    public function createOne($data)
    {
        $row = parent::createOne($data);

        app()->getMessages()->addSuccess(
            "Your category has been created"
        );

        return $row;
    }

    /**
     * @param array $data
     * @return bool|void
     */
    public function validateCreate($data)
    {
        $this->checkName($data);
        $this->checkAlias($data);

        $alias = isset($data['alias']) ? $data['alias'] : null;

        if ($this->getTable()->findRowWhere(['alias' => $alias])) {
            $this->addError(
                __('Category with alias "%s" already exists', esc($alias)),
                'alias'
            );

        }

    }

    /**
     * @param mixed $id
     * @param array $data
     * @return bool|void
     */
    public function validateUpdate($id, $data)
    {
        $this->checkName($data);
        $this->checkAlias($data);

        if ($this->getTable()->isDublicateAlias($data)) {
            $this->addError(
                __('Category with alias "%s" already exists', esc($data['alias'])),
                'alias'
            );
        }

    }

    /**
     * checkName
     *
     * @param $data
     * @return void
     */
    protected
    function checkName($data)
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
     * @param $data
     */
    protected
    function checkAlias($data)
    {
        $alias = isset($data['alias']) ? $data['alias'] : null;
        if (empty($alias)) {
            $this->addError('Alias can\'t be empty', 'alias');
        }
        if (strlen($alias) > 64) {
            $this->addError('Alias can\'t be bigger than 64 symbols', 'alias');
        }

    }

    /**
     * @param mixed $data
     * @return int|void
     */
    public
    function deleteOne($data)
    {
        $table = Categories\Table::getInstance();
        $tree = $table->buildTree($data['id']);


        if (!isset($tree[0]['children'])) {
            parent::deleteOne($data);
        } else {
            $allSubCategories = $this->_treeToArray($tree);

            foreach ($allSubCategories as $categoryid) {
                parent::deleteOne(['id' => $categoryid]);
            }

        }

    }

    /**
     * @param array $tree
     * @return array
     */
    private
    function _treeToArray($tree)
    {
        foreach ($tree as $node) {

            if (empty($node['children'])) {
                $this->_arrayWithTree[] = $node['id'];
            } else {
                $this->_arrayWithTree[] = $node['id'];
                $this->_treeToArray($node['children']);
            }
        }

        return $this->_arrayWithTree;
    }
}
