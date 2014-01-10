<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Test;


/**
 * Test Grid based on SQL
 *
 * @category Application
 * @package  Test
 */
class SqlGrid extends \Bluz\Grid\Grid
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

         return $this;
    }
}
