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
namespace Application\Pages;

use \Bluz\Crud\ValidationException;

/**
 * @category Application
 * @package  Pages
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
        $title = $this->getData('title');
        if (empty($title)) {
            $this->addError('title', 'Title can\'t be empty');
        }

        // alias update
        $alias = $this->getData('alias');
        if (empty($alias)) {
            $this->addError('alias', 'Alias can\'t be empty');
        } elseif (!preg_match('/^[a-zA-Z0-9_\.\-]+$/i', $alias)) {
            $this->addError('alias', 'Alias should contains only Latin characters, dots and dashes');
        } elseif ($row = $this->getTable()->findRowWhere(['alias' => $alias])) {
            if ($row->id != $this->getData('id')) {
                $this->addError('alias', 'Alias "'.htmlentities($alias).'" already exists');
            }
        }

        // content validator
        $content = $this->getData('content');
        if (empty($content) or trim(strip_tags($content, '<img>')) == '') {
            $this->addError('content', 'Content can\'t be empty');
        }

        // validate entity
        // ...
        if (sizeof($this->errors)) {
            throw new ValidationException('Validation error, please check errors stack');
        }
    }
}
