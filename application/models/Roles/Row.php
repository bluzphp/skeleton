<?php

/**
 * @copyright Bluz PHP Team
 * @link      https://github.com/bluzphp/skeleton
 */

declare(strict_types=1);

namespace Application\Roles;

use Bluz\Validator\Traits\Validator;

/**
 * Row of User Role
 *
 * @package Application\Roles
 *
 * @property integer $id
 * @property string $name
 */
class Row extends \Bluz\Db\Row
{
    use Validator;

    /**
     * {@inheritdoc}
     *
     * @throws \Bluz\Validator\Exception\ComponentException
     */
    protected function beforeInsert(): void
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
     * {@inheritdoc}
     *
     * @throws \Bluz\Validator\Exception\ComponentException
     */
    protected function beforeUpdate(): void
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
                    return $this->clean['name'] !== $name;
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
    public function isBasic(): bool
    {
        return in_array(strtolower($this->name), Table::getInstance()->getBasicRoles(), false);
    }
}
