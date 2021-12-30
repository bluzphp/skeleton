<?php

/**
 * @copyright Bluz PHP Team
 * @link      https://github.com/bluzphp/skeleton
 */

declare(strict_types=1);

namespace Application\Events;

/**
 * Table of Events
 *
 * @package  Application\Events
 */
class Table extends \Bluz\Db\Table
{
    /**
     * @var string
     */
    protected $name = 'events';

    /**
     * @var string
     */
    protected $rowClass = Row::class;

    /**
     * Primary key(s)
     * @var array
     */
    protected $primary = ['id'];
}
