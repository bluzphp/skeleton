<?php

/**
 * @copyright Bluz PHP Team
 * @link      https://github.com/bluzphp/skeleton
 */

declare(strict_types=1);

namespace Application\Events;

use Bluz\Grid\Source\SelectSource;

/**
 * Grid of Events
 *
 * @package  Application\Events
 *
 * @method   Row[] getData()
 */
class Grid extends \Bluz\Grid\Grid
{
    /**
     * @var string
     */
    protected $uid = 'events';

    /**
     * @return void
     */
    public function init(): void
    {
        // Current table as source of grid
        $adapter = new SelectSource();
        $adapter->setSource(Table::select());

        $this->setAdapter($adapter);
        $this->setDefaultLimit(25);
        $this->setDefaultOrder('events.id', Grid::ORDER_DESC);
        $this->setAllowFilters([
            'id',
            'event',
            'target',
            'created',
            'updated',
        ]);
        $this->setAllowOrders([
            'id',
            'event',
            'target',
            'created',
            'updated',
        ]);
    }
}
