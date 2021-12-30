<?php

/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

declare(strict_types=1);

namespace Application\Events;

/**
 * Class Service
 *
 * @package Application\Events
 */
class Service
{
    /**
     * Set option value for use it from any application place
     *
     * @param string $event
     * @param string $target
     * @param array $data
     * @return mixed
     * @throws \Bluz\Db\Exception\DbException
     * @throws \Bluz\Db\Exception\InvalidPrimaryKeyException
     * @throws \Bluz\Db\Exception\TableNotFoundException
     */
    public static function fire(string $event, string $target, array $data = [])
    {
        /** @var Row $row */
        $row = Table::create();
        $row->event = $event;
        $row->target = $target;
        $row->data = json_encode($data);
        return $row->save();
    }
}
