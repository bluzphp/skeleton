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

use \Bluz\Crud\Table;

/**
 * @category Application
 * @package  Pages
 *
 * @author   Anton Shevchuk
 * @created  03.09.12 13:11
 */
class Crud extends Table
{
    /**
     * {@inheritdoc}
     */
    public function validate($primary, $data)
    {
        // name validator
        $title = isset($data['title'])?$data['title']:null;
        if (empty($title)) {
            $this->addError('Title can\'t be empty', 'title');
        }

        // alias update
        $alias = isset($data['alias'])?$data['alias']:null;
        if (empty($alias)) {
            $this->addError('Alias can\'t be empty', 'alias');
        } elseif (!preg_match('/^[a-zA-Z0-9_\.\-]+$/i', $alias)) {
            $this->addError('Alias should contains only Latin characters, dots and dashes', 'alias');
        } elseif ($row = $this->getTable()->findRowWhere(['alias' => $alias])) {
            if ($row->id != $data['id']) {
                $this->addError(
                    __('Alias "%s" already exists', esc($alias)),
                    'alias'
                );
            }
        }

        // content validator
        $content = isset($data['content'])?$data['content']:null;
        if (empty($content) or trim(strip_tags($content, '<img>')) == '') {
            $this->addError('Content can\'t be empty', 'content');
        }
    }
}
