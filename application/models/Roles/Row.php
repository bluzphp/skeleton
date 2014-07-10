<?php
/**
 * User
 *
 * @category Application
 * @package  Roles
 */
namespace Application\Roles;

use Bluz\Validator\Traits\Validator;
use Bluz\Validator\Validator as v;

/**
 * Class Row
 * @package Application\Roles
 *
 * @property integer $id
 * @property string $name
 */
class Row extends \Bluz\Db\Row
{
    use Validator;

    /**
     * Before Insert/Update
     * @return void
     */
    protected function beforeInsert()
    {
        $this->addValidator(
            'name',
            v::required()->latin(),
            v::callback(function ($name) {
                return !Table::getInstance()->findRowWhere(['name'=>$name]);
            })->setError('Role name "{{input}}" already exists')
        );
    }

    /**
     * Before Update
     * @return void
     */
    protected function beforeUpdate()
    {
        $this->addValidator(
            'name',
            v::required()->latin(),
            v::callback(function ($name) {
                return !in_array(strtolower($name), Table::getInstance()->getBasicRoles());
            })->setError('Role "{{input}}" is basic and can\'t be editable'),
            v::callback(function ($name) {
                return $this->clean['name'] != $name;
            })->setError('Role name "{{input}}" the same as original'),
            v::callback(function ($name) {
                return !Table::getInstance()->findRowWhere(['name'=>$name]);
            })->setError('Role name "{{input}}" already exists')
        );
    }

    /**
     * isBasic
     *
     * @return boolean
     */
    public function isBasic()
    {
        return in_array(strtolower($this->name), Table::getInstance()->getBasicRoles());
    }
}
