<?php

/**
 * @copyright Bluz PHP Team
 * @link      https://github.com/bluzphp/skeleton
 */

declare(strict_types=1);

namespace Application\Events;

/**
 * Row of Event
 *
 * @package  Application\Events
 *
 * @property integer $id
 * @property string $event
 * @property string $target
 * @property string $data
 * @property string $created
 * @property string $updated
 *
 * @OA\Schema(schema="event", title="event", required={"event", "target"})
 * @OA\Property(property="event", type="string", description="Options namespace", example="default")
 * @OA\Property(property="target", type="string", description="Key", example="Some key")
 * @OA\Property(property="data", type="string", description="JSON data", example="{some:'value'}")
 * @OA\Property(property="created", type="string", format="date-time", description="Created date",
 *     example="2017-03-17 19:06:28")
 * @OA\Property(property="updated", type="string", format="date-time", description="Last updated date",
 *     example="2017-03-17 19:06:28")
 */
class Row extends \Bluz\Db\Row
{
    /**
     * @return void
     */
    public function beforeInsert(): void
    {
    }

    /**
     * @return void
     */
    public function beforeUpdate(): void
    {
    }
}
