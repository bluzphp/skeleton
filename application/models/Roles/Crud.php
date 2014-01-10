<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Roles;

/**
 * Crud
 *
 * @category Application
 * @package  Test
 *
 * @author   Anton Shevchuk
 * @created  03.09.12 13:11
 */
class Crud extends \Bluz\Crud\Table
{
    /**
     * validate Name
     *
     * @param string $name
     * @return void
     */
    public function checkName($name)
    {
        if (empty($name)) {
            $this->addError(__('Role name can\'t be empty'), 'name');
        }
        if (!preg_match('/^[a-zA-Z0-9-_ ]+$/', $name)) {
            $this->addError(__('Name should contains only Latin characters'), 'name');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function validateCreate($data)
    {
        // role name validator
        $name = isset($data['name'])?$data['name']:null;

        $this->checkName($name);

        if (Table::getInstance()->findRowWhere(['name'=>$name])) {
            $this->addError(__('Role name "%s" already exists', $name), 'name');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function validateUpdate($id, $data)
    {
        // role name validator
        $name = isset($data['name'])?$data['name']:null;

        $this->checkName($name);

        if (in_array(strtolower($name), Table::getInstance()->getBasicRoles())) {
            $this->addError(__('Role name "%s" is basic and can\'t be editable', $name), 'name');
        };

        $originalRow = $this->readOne($id);

        if ($originalRow->name == $name) {
            $this->addError(__('Role name "%s" the same as original', $name), 'name');
        } elseif (Table::getInstance()->findRowWhere(['name'=>$name])) {
            $this->addError(__('Role name "%s" already exists', $name), 'name');
        }
    }
}
