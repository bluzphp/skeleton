<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Roles;

use Bluz\Validator\Validator as v;
use Bluz\Validator\ValidatorBuilder;

/**
 * Crud
 *
 * @package  Application\Test
 *
 * @method   Table getTable()
 *
 * @author   Anton Shevchuk
 * @created  03.09.12 13:11
 */
class Crud extends \Bluz\Crud\Table
{
    /**
     * {@inheritdoc}
     */
    public function validateCreate($data)
    {
        // role name validator
        $validator = new ValidatorBuilder();
        $validator->add(
            'name',
            v::required()->latin(),
            v::callback(function ($name) {
                return !Table::getInstance()->findRowWhere(['name'=>$name]);
            })->setError('Role name "{{input}}" already exists')
        );

        if (!$validator->validate($data)) {
            $this->setErrors($validator->getErrors());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function validateUpdate($id, $data)
    {
        // role name validator
        $validator = new ValidatorBuilder();
        $validator->add(
            'name',
            v::required()->latin(),
            v::callback(function ($name) {
                return !in_array(strtolower($name), Table::getInstance()->getBasicRoles());
            })->setError('Role "{{input}}" is basic and can\'t be editable'),
            v::callback(function ($name) use ($id) {
                $originalRow = $this->readOne($id);
                return $originalRow->name != $name;
            })->setError('Role name "{{input}}" the same as original'),
            v::callback(function ($name) use ($id) {
                return !Table::getInstance()->findRowWhere(['name'=>$name]);
            })->setError('Role name "{{input}}" already exists')
        );

        if (!$validator->validate($data)) {
            $this->setErrors($validator->getErrors());
        }
    }
}
