<?php
/**
 * Copyright (c) 2013 by Bluz PHP Team
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

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
    public function isAliasDuplicated($data)
    {
        $alias = $data['alias'];

        $select = $this->select()
                ->where('alias = ?', $alias)
                ->andWhere('id != ?', $data['id'])
                ->andWhere('parentId != ?', $data['parentId']);

        return (bool)count($select->execute());
    }

    /**
     * @return array|mixed
     */
    public function getAllRootCategory()
    {
        return $this->select()->where('parentId IS NULL')->orderBy('created', 'DESC')->execute();
    }
}
