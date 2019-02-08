<?php
/**
 * @copyright Bluz PHP Team
 * @link      https://github.com/bluzphp/skeleton
 */

declare(strict_types=1);

namespace Application\Pages;

use Bluz\Grid\Source\SelectSource;

/**
 * Grid of Pages
 *
 * @package  Application\Pages
 *
 * @method   Row[] getData()
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
    public function init(): void
    {
        // Setup source
        $adapter = new SelectSource();
        $adapter->setSource(Table::select());

        $this->setAdapter($adapter);
        $this->setDefaultLimit(25);
        $this->setAllowOrders(['title', 'id', 'created', 'updated']);
        $this->setAllowFilters(['title', 'alias', 'description', 'content', 'id']);
    }
}
