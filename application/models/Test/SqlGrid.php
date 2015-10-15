<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Test;

use Bluz\Grid\Grid;

/**
 * Test Grid based on SQL
 *
 * @category Application
 * @package  Test
 */
class SqlGrid extends Grid
{
    protected $uid = 'sql';

    /**
     * init
     *
     * @return self
     */
    public function init()
    {
        // Array
        $adapter = new \Bluz\Grid\Source\SqlSource();
        $adapter->setSource('SELECT * FROM test');

        $this->setAdapter($adapter);
        $this->setDefaultLimit(15);
        $this->setAllowOrders(['name', 'id', 'status']);
        $this->setAllowFilters(['status', 'id']);
        $this->setDefaultOrder('name', Grid::ORDER_DESC);

        return $this;
    }
}
