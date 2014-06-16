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
 * Class Table
 *
 * @package Application\Categories
 *
 * @method  static Row findRow($primaryKey)
 * @method  static Row findRowWhere($whereList)
 */
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
        return $this->generateTree($this->prepareTree(), $id);
    }

    /**
     * @param string $alias
     * @return array
     */
    public function buildTreeByAlias($alias)
    {
        $current = $this->findRow(['alias' => $alias]);

        return $this->generateTree($this->prepareTree(), $current['id']);
    }

    /**
     * @return array
     */
    public function prepareTree()
    {
        $result = $this->getAdapter()->fetchGroup('SELECT id, categories.* FROM categories ORDER BY `order`');
        $result = array_map('reset', $result);

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
     * @return array|mixed
     */
    public function getAllRootCategory()
    {
        return $this->select()->where('parentId IS NULL')->orderBy('created', 'DESC')->execute();
    }
}
