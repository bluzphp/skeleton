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
 * Options Row
 *
 * @property string $namespace
 * @property string $key
 * @property string $value
 * @property string $description
 * @property string $created
 * @property string $updated
 *
 * @category Application
 * @package  Options
 */
class Row extends \Bluz\Db\Row
{
    /**
     * {@inheritdoc}
     */
    protected function afterRead()
    {
        $this->value = unserialize($this->value);
    }

    /**
     * {@inheritdoc}
     */
    protected function beforeSave()
    {
        $this->value = serialize($this->value);
    }

    /**
     * {@inheritdoc}
     */
    protected function beforeUpdate()
    {
        $this->updated = gmdate('Y-m-d H:i:s');
    }
}
