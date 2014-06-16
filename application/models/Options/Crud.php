<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Options;

use Bluz\Validator\Validator as v;
use Bluz\Validator\ValidatorBuilder;

/**
 * Class Crud of Options
 * @package Application\Options
 *
 * @method Table getTable()
 */
class Crud extends \Bluz\Crud\Table
{
    /**
     * {@inheritdoc}
     */
    public function validate($id, $data)
    {
        $validator = new ValidatorBuilder();
        $validator->add(
            'namespace',
            v::required()->slug()
        )->add(
            'key',
            v::required()->slug()
        );

        if (!$validator->validate($data)) {
            $this->setErrors($validator->getErrors());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function validateCreate($data)
    {
        $key = isset($data['key'])?$data['key']:null;
        $namespace = isset($data['namespace'])?$data['namespace']:null;
        // unique validator
        if ($this->getTable()->findRowWhere(['key' => $key, 'namespace' => $namespace])) {
            $this->addError(
                __('Key name "%s" already exists in namespace "%s"', esc($key), esc($namespace)),
                'key'
            );
        }
    }
}
