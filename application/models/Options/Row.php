<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Options;

use Bluz\Validator\Traits\Validator;
use Bluz\Validator\Validator as v;

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
    use Validator;

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

        $this->addValidator(
            'namespace',
            v::required()->slug()
        );
        $this->addValidator(
            'key',
            v::required()->slug()
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function beforeInsert()
    {
        // unique validator
        $this->addValidator(
            'key',
            v::callback(function () {
                return !$this->getTable()->findRowWhere(['key' => $this->key, 'namespace' => $this->namespace]);
            })->setError('Key name "{{input}}" already exists')
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function beforeUpdate()
    {
        $this->updated = gmdate('Y-m-d H:i:s');
    }
}
