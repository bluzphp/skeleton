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
class TableGrid extends Grid
{
    protected $uid = 'tbl';

    /**
     * init
     *
     * @return self
     */
    public function init()
    {
        // Array
        $adapter = new \Bluz\Grid\Source\SelectSource();

        $select = Table::select();

        $adapter->setSource($select);

        $this->setAdapter($adapter);
        $this->setDefaultLimit(15);
        $this->setAllowOrders(['name', 'id', 'status']);
        $this->setAllowFilters(['status', 'id']);

        return $this;
    }
}
