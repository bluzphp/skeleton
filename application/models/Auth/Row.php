<?php
/**
 * @copyright Bluz PHP Team
 * @link      https://github.com/bluzphp/skeleton
 */

declare(strict_types=1);

namespace Application\Auth;

use Bluz\Auth\Model\AbstractRow;

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
     * {@inheritdoc}
     */
    public function beforeInsert() : void
    {
        $this->created = gmdate('Y-m-d H:i:s');
    }

    /**
     * {@inheritdoc}
     */
    public function beforeUpdate() : void
    {
        $this->updated = gmdate('Y-m-d H:i:s');
    }
}
