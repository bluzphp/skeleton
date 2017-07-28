<?php
/**
 * @copyright Bluz PHP Team
 * @link      https://github.com/bluzphp/skeleton
 */

declare(strict_types=1);

namespace Application\Roles;

use Bluz\Validator\Traits\Validator;

/**
 * User Role
 *
 * @package Application\Roles
 *
 * @property integer $id
 * @property string  $name
 */
class Row extends \Bluz\Db\Row
{
    use Validator;

    /**
     * Before Insert/Update
     *
     * @return void
     */
    protected function beforeInsert()
    {
        $this->addValidator('name')
            ->required()
            ->latin()
            ->callback(
                function ($name) {
                    return !Table::findRowWhere(['name' => $name]);
                }
            )
            ->setDescription('Role already exists');
    }

    /**
     * Before Update
     *
     * @return void
     */
    protected function beforeUpdate()
    {
        $this->addValidator('name')
            ->required()
            ->latin()
            ->callback(
                function ($name) {
                    return !in_array(strtolower($name), Table::getInstance()->getBasicRoles(), false);
                },
                'Role is basic and can\'t be editable'
            )
            ->callback(
                function ($name) {
                    return $this->clean['name'] != $name;
                },
                'Role name is the same as original'
            )
            ->callback(
                function ($name) {
                    return !Table::findRowWhere(['name' => $name]);
                },
                'Role already exists'
            );
    }

    /**
     * isBasic
     *
     * @return boolean
     */
    public function isBasic() : bool
    {
        return in_array(strtolower($this->name), Table::getInstance()->getBasicRoles(), false);
    }
}
