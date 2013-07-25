<?php
namespace Application\Pages;

use Bluz\Exception;

/**
 * Pages Table
 *
 * @category Application
 * @package  Pages
 */
class Table extends \Bluz\Db\Table
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'pages';

    /**
     * Primary key(s)
     * @var array
     */
    protected $primary = array('id');

    /**
     * getByAlias
     *
     * @param $alias
     * @return Row
     */
    public function getByAlias($alias)
    {
        return $this->findRowWhere(['alias'=>$alias]);
    }
}
