<?php
/**
 * @copyright Bluz PHP Team
 * @link      https://github.com/bluzphp/skeleton
 */

declare(strict_types=1);

namespace Application\Pages;

use Bluz\Grid\Source\SqlSource;

/**
 * Grid of Pages
 *
 * @method   Row[] getData()
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
     * {@inheritdoc}
     *
     * @throws \Bluz\Grid\GridException
     */
    public function init() : void
    {
        // Setup source
        $adapter = new SqlSource();
        $adapter->setSource('SELECT * FROM pages');

        $this->setAdapter($adapter);
        $this->setDefaultLimit(25);
        $this->setAllowOrders(['title', 'id', 'created', 'updated']);
        $this->setAllowFilters(['title', 'alias', 'description', 'content', 'id']);
    }
}
