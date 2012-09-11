<?php
/**
 * Copyright (c) 2012 by Bluz PHP Team
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
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
class Crud extends \Bluz\Crud\Crud
{
    /**
     * validateName
     *
     * @param $name
     * @return void
     */
    public function validateName($name)
    {
        if (empty($name)) {
           $this->addError('name', 'Role name can\'t be empty');
        }
        if (!preg_match('/[a-zA-Z0-9-_]+/', $name)) {
           $this->addError('name', 'Name should contains only Latin characters');
        }
    }

    /**
     * @throws \Bluz\Crud\ValidationException
     */
    public function validateCreate()
    {
        // role name validator
        $name = $this->getData('name');

        $this->validateName($name);

        if (Table::getInstance()->findRowWhere(['name'=>$name])) {
            $this->addError('name', 'Role "'.$name.'" already exists');
        }

        parent::validate();
    }

    /**
     * @throws \Bluz\Crud\ValidationException
     */
    public function validateUpdate($originalRow)
    {
        // role name validator
        $name = $this->getData('name');

        $this->validateName($name);

        if (in_array(strtolower($name), Table::getInstance()->getBasicRoles())) {
            $this->addError('name', 'Role name "'.$name.'" is basic and can\'t be editable');
        };

        if ($originalRow->name == $name) {
            $this->addError('name', 'Role name "'.$name.'" the same as original');
        } elseif (Table::getInstance()->findRowWhere(['name'=>$name])) {
            $this->addError('name', 'Role "'.$name.'" already exists');
        }

        parent::validate();
    }
}
