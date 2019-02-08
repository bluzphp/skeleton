<?php
/**
 * @copyright Bluz PHP Team
 * @link      https://github.com/bluzphp/skeleton
 */

declare(strict_types=1);

namespace Application\Pages;

/**
 * Table of Pages
 *
 * @package  Application\Pages
 *
 * @method   static ?Row findRow($primaryKey)
 * @see      \Bluz\Db\Table::findRow()
 * @method   static ?Row findRowWhere($whereList)
 * @see      \Bluz\Db\Table::findRowWhere()
 */
class Table extends \Bluz\Db\Table
{
    /**
     * Table
     *
     * @var string
     */
    protected $name = 'pages';

    /**
     * Primary key(s)
     *
     * @var array
     */
    protected $primary = ['id'];

    /**
     * Get page by Alias
     *
     * @param $alias
     *
     * @return Row
     * @throws \Bluz\Db\Exception\DbException
     */
    public function getByAlias($alias): ?Row
    {
        return static::findRowWhere(['alias' => $alias]);
    }

    /**
     * Init table relations
     *
     * @return void
     */
    public function init(): void
    {
        $this->linkTo('userId', 'Users', 'id');
    }
}
