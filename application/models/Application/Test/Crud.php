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

use Bluz\Crud\Table;

/**
 * Crud based on Db\Table
 *
 * @category Application
 * @package  Test
 *
 * @author   Anton Shevchuk
 * @created  03.09.12 13:11
 */
class Crud extends Table
{
    /**
     * {@inheritdoc}
     */
    public function readSet($offset = 0, $limit = 10, array $params = array())
    {
        $select = app()->getDb()
            ->select('*')
            ->from('test', 't');

        if ($limit) {
            $selectPart = $select->getQueryPart('select');
            $selectPart[0] = 'SQL_CALC_FOUND_ROWS ' . current($selectPart);
            $select->select($selectPart);

            $select->setLimit($limit);
            $select->setOffset($offset);
        }

        $result = $select->execute('\\Application\\Test\\Row');

        if ($limit) {
            $total = app()->getDb()->fetchOne('SELECT FOUND_ROWS()');
        } else {
            $total = sizeof($result);
        }

        if (sizeof($result) < $total) {
            http_response_code(206);
            header('Content-Range: items '.$offset.'-'.($offset+sizeof($result)).'/'. $total);
        }

        return $result;
    }

    /**
     * checkName
     *
     * @param $name
     * @return void
     */
    protected function checkName($name)
    {
        if (empty($name)) {
            $this->addError('name', 'Name can\'t be empty');
        } elseif (!preg_match('/^[a-zA-Z .-]+$/i', $name)) {
            $this->addError('name', 'Name should contains only Latin characters');
        }
    }

    /**
     * checkEmail
     *
     * @param $email
     * @return void
     */
    protected function checkEmail($email)
    {
        if (empty($email)) {
            $this->addError('email', 'Email can\'t be empty');
        } elseif (!$email = filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->addError('email', 'Email has invalid format');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function validateCreate(array $data)
    {
        // name validator
        $name = isset($data['name'])?$data['name']:null;
        $this->checkName($name);

        // email validator
        $email = isset($data['email'])?$data['email']:null;
        $this->checkEmail($email);
    }

    /**
     * {@inheritdoc}
     */
    public function validateUpdate($id, array $data)
    {
        // name validator
        if (isset($data['name'])) {
            $this->checkName($data['name']);
        }

        // email validator
        if (isset($data['email'])) {
            $this->checkEmail($data['email']);
        }
    }
}
