<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Pages;

use Bluz\Grid\Source\SqlSource;

/**
 * Grid of Pages
 *
 * @package  Application\Pages
 */
class Grid extends \Bluz\Grid\Grid
{
    /**
     * @var string
     */
    protected $uid = 'pages';

    /**
     * init
     *
     * @return self
     */
    public function init()
    {
         // Setup source
         $adapter = new SqlSource();
         $adapter->setSource('SELECT * FROM pages');

         $this->setAdapter($adapter);
         $this->setDefaultLimit(25);
         $this->setAllowOrders(['title', 'id', 'created', 'updated']);
         $this->setAllowFilters(['title', 'alias', 'description', 'content', 'id']);
         return $this;
    }
}
