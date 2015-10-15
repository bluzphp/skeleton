<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Options;

/**
 * Grid based on SQL
 *
 * @category Application
 * @package  Options
 */
class Grid extends \Bluz\Grid\Grid
{
    /**
     * @var string
     */
    protected $uid = 'options';

    /**
     * init
     *
     * @return self
     */
    public function init()
    {
         // Array
         $adapter = new \Bluz\Grid\Source\SqlSource();
         $adapter->setSource('SELECT * FROM options');

         $this->setAdapter($adapter);
         $this->setDefaultLimit(25);
         $this->setAllowOrders(['id', 'key', 'namespace', 'value', 'created', 'deleted']);

         return $this;
    }
}
