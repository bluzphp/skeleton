<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Auth;

use Bluz\Auth\AbstractRow;

/**
 * Auth Row
 *
 * @package  Application\Auth
 *
 * @property string $created
 * @property string $updated
 *
 * @author   Anton Shevchuk
 * @created  24.10.12 11:57
 */
class Row extends AbstractRow
{
    /**
     * __insert
     *
     * @return void
     */
    public function beforeInsert()
    {
        $this->created = gmdate('Y-m-d H:i:s');
    }

    /**
     * __update
     *
     * @return void
     */
    public function beforeUpdate()
    {
        $this->updated = gmdate('Y-m-d H:i:s');
    }
}
