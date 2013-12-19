<?php

/**
 * @namespace
 */
namespace Application\Categories;


class Table extends \Bluz\Db\Table
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'categories';

    /**
     * Primary key(s)
     * @var array
     */
    protected $primary = array('id');


    /**
     * @param int $id
     * @return array
     */
    public function getAllCategories($id = null)
    {
        $select = $this->select();

        if ($id) {
            $select->where('id != ?', $id);
        }

        return $select->execute();
    }

    /**
     * @param int $id
     * @return array
     */
    public function buildTree($id = null)
    {
        return $this->generateTree($this->prepeadTree(), $id);
    }

    /**
     * @param string $alias
     * @return array
     */
    public function buildTreeByAlias($alias)
    {
        $current = $this->findRow(['alias' => $alias]);

        return $this->generateTree($this->prepeadTree(), $current['id']);
    }

    /**
     * @return array
     */
    public function prepeadTree()
    {
        $result = [];

        $select = $this->select()
                ->orderBy('`order`', 'ASC');

        foreach ($select->execute() as $category) {
            $result[$category->id] = $category->toArray();
        }

        return $result;
    }

    /**
     * @param array $categoryList
     * @param int $id
     * @return array
     */
    public function generateTree($categoryList, $id)
    {
        foreach ($categoryList as $categoryId => $category) {
            if ($category['parentId']) {
                $categoryList[$category['parentId']]['children'][$categoryId] =& $categoryList[$categoryId];
            }
        }

        return array($categoryList[$id]);
    }

    /**
     * @param array $data
     * @return bool
     */
    public function  isAliasDuplicated($data)
    {
        $alias = $data['alias'];

        $select = $this->select()
                ->where('alias = ?', $alias)
                ->andWhere('id != ?', $data['id']);

        return (bool)count($select->execute());
    }

    /**
     * @return array|mixed
     */
    public function getAllRootCategory()
    {
        return $this->select()->where('parentId = ?', 0)->orderBy('created', 'DESC')->execute();
    }
}
