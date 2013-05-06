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
namespace Application\Test;

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
class Crud extends \Bluz\Crud\Crud
{
    /**
     * @throws ValidationException
     */
    public function validate()
    {
        // name validator
        $name = $this->getData('name');
        if (empty($name)) {
            $this->addError('name', 'Name can\'t be empty');
        } elseif (!preg_match('/^[a-zA-Z .-]+$/i', $name)) {
            $this->addError('name', 'Name should contains only Latin characters');
        }

        // email validator
        $email = $this->getData('email');
        if (empty($email)) {
            $this->addError('email', 'Email can\'t be empty');
        }

        // validate entity
        // ...
        if (sizeof($this->errors)) {
            throw new ValidationException('Validation error, please check errors stack');
        }
    }
}
