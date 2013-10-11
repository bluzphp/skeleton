<?php
/**
 * Copyright (c) 2013 by Bluz PHP Team
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

use \Bluz\Crud\ValidationException;

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
            $this->addError('name', __('Role name can\'t be empty'));
        }
        if (!preg_match('/^[a-zA-Z0-9-_ ]+$/', $name)) {
            $this->addError('name', __('Name should contains only Latin characters'));
        }
    }

    /**
     * @throws ValidationException
     */
    public function validateCreate($data)
    {
        // role name validator
        $name = isset($data['name'])?$data['name']:null;

        $this->checkName($name);

        if (Table::getInstance()->findRowWhere(['name'=>$name])) {
            $this->addError('name', __('Role name "%s" already exists', $name));
        }
    }

    /**
     * @throws ValidationException
     */
    public function validateUpdate($id, $data)
    {
        // role name validator
        $name = isset($data['name'])?$data['name']:null;

        $this->checkName($name);

        if (in_array(strtolower($name), Table::getInstance()->getBasicRoles())) {
            $this->addError('name', __('Role name "%s" is basic and can\'t be editable', $name));
        };

        $originalRow = $this->readOne($id);

        if ($originalRow->name == $name) {
            $this->addError('name', __('Role name "%s" the same as original', $name));
        } elseif (Table::getInstance()->findRowWhere(['name'=>$name])) {
            $this->addError('name', __('Role name "%s" already exists', $name));
        }
    }
}
