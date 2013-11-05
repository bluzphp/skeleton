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
namespace Application\Options;

use \Bluz\Crud\Table;

/**
 * Crud
 *
 * @category Application
 * @package  Options
 */
class Crud extends Table
{
    /**
     * {@inheritdoc}
     */
    public function validate($id, $data)
    {
        // key name validator
        $key = isset($data['key'])?$data['key']:null;
        if (empty($key)) {
            $this->addError('Key name can\'t be empty', 'key');
        } elseif (!preg_match('/^[a-zA-Z .-]+$/i', $key)) {
            $this->addError('Key name should contains only Latin characters', 'key');
        }

        // namespace validator
        $namespace = isset($data['namespace'])?$data['namespace']:null;
        if (empty($namespace)) {
            $this->addError('Name can\'t be empty', 'namespace');
        } elseif (!preg_match('/^[a-zA-Z .-]+$/i', $namespace)) {
            $this->addError('Name should contains only Latin characters', 'namespace');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function validateCreate($data)
    {
        $key = isset($data['key'])?$data['key']:null;
        $namespace = isset($data['namespace'])?$data['namespace']:null;
        // unique validator
        if ($row = $this->getTable()->findRowWhere(['key' => $key, 'namespace' => $namespace])) {
            $this->addError(
                __('Key name "%s" already exists in namespace "%s"', esc($key), esc($namespace)),
                'key'
            );
        }
    }
}
