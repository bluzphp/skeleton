<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Categories;

use Bluz\Proxy\Db;

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
     * @return array
     */
    public function getRootCategories()
    {
        return $this->select()->where('parentId IS NULL')->orderBy('created', 'DESC')->execute();
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

        return $this->buildTree($current['id']);
    }

    /**
     * Get all categories
     * @return array
     */
    protected function prepareTree()
    {
        $result = Db::fetchGroup('SELECT id, categories.* FROM categories ORDER BY `order`');
        $result = array_map('reset', $result);
        return $result;
    }

    /**
     * @param array $categoryList
     * @param int $id
     * @return array
     */
    protected function generateTree($categoryList, $id)
    {
        foreach ($categoryList as $categoryId => $category) {
            if ($category['parentId']) {
                $categoryList[$category['parentId']]['children'][$categoryId] =& $categoryList[$categoryId];
            }
        }

        return array($categoryList[$id]);
    }
}
