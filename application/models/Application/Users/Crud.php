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
namespace Application\Users;

/**
 * Crud
 *
 * @category Application
 * @package  Users
 *
 * @author   Anton Shevchuk
 * @created  30.10.12 16:11
 */
class Crud extends \Bluz\Crud\Crud
{
    /**
     * @throws \Bluz\Crud\ValidationException
     */
    public function validate()
    {
        // name validator
        $login = $this->getData('login');
        if (empty($login)) {
            $this->addError('login', 'Login can\'t be empty');
        }

        // email validator
        $email = $this->getData('email');
        if (empty($email)) {
            $this->addError('email', 'Email can\'t be empty');
        }

        // validate entity
        if (sizeof($this->errors)) {
            throw new \Bluz\Crud\ValidationException('Validation error, please check errors stack');
        }
    }

    /**
     * @throws \Bluz\Crud\ValidationException
     * @return boolean
     */
    public function create()
    {

        if ($this->getTable()->findRowWhere(['login' => $this->getData('login')])) {
            $this->addError('login', 'User with login "'.htmlentities($this->getData('login')).'" already exists');
        }

        if ($this->getTable()->findRowWhere(['email' => $this->getData('email')])) {
            $this->addError('email', 'User with email "'.htmlentities($this->getData('email')).'" already exists');
        }

        $this->validateCreate();

        /** @var $row Row */
        $row = $this->getTable()->create();
        $row->setFromArray($this->data);
        $row->status = Row::STATUS_PENDING;

        $userId = $row->save();
    }
}
