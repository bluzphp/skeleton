<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Pages;

use Application\Users;

/**
 * Pages Table
 *
 * @package  Application\Pages
 *
 * @method   static Row findRow($primaryKey)
 * @method   static Row findRowWhere($whereList)
 */
class Table extends \Bluz\Db\Table
{
    /**
     * Table
     * @var string
     */
    protected $table = 'pages';

    /**
     * Primary key(s)
     * @var array
     */
    protected $primary = array('id');

    /**
     * Get page by Alias
     * @param $alias
     * @return Row
     */
    public function getByAlias($alias)
    {
        return $this->findRowWhere(['alias'=>$alias]);
    }

    /**
     * Init table relations
     * @return void
     */
    public function init()
    {
        $this->linkTo('userId', 'Users', 'id');
    }
}
