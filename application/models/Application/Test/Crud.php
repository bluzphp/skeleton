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
namespace Application\Test;

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
     * @var array
     */
    protected $data = array();

    /**
     * @var \Bluz\Db\Table
     */
    protected $table;

    /**
     * @var array
     */
    protected $errors = array();

    /**
     * Result of last CRUD operation (on of get/put/post/delete)
     * @var mixed
     */
    protected $result;

    /**
     * @return Crud
     */
    public function processRequest()
    {
        $request = $this->getApplication()->getRequest();

        // get data from request
        $this->data = $request->getParam('data');

        // rewrite REST with "method" param
        $method = $request->getParam('method', $request->getMethod());

        // switch by method
        switch ($method) {
            case 'post':
                $this->result = $this->create();
                break;
            case 'put':
                $this->result = $this->update();
                break;
            case 'delete':
                $this->result = $this->delete();
                break;
            case 'get':
            default:
                $this->result = $this->get();
                break;
        }

        return $this;
    }

    /**
     * setTable
     *
     * @param \Bluz\Db\Table $table
     * @return self
     */
    public function setTable(\Bluz\Db\Table $table)
    {
        $this->table = $table;
        return $this;
    }

    /**
     * getTable
     * 
     * @return \Bluz\Db\Table
     */
    public function getTable()
    {
        if (!$this->table) {
            $crudClass = get_called_class();
            $tableClass = substr($crudClass, 0, strrpos($crudClass, '\\', 1)+1) . 'Table';

            /**
             * @var \Bluz\Db\Table $tableClass
             */
            $table = $tableClass::getInstance();

            $this->setTable($table);
        }
        return $this->table;
    }

    /**
     * getData
     * 
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * getResult
     *
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @throws \Bluz\Crud\ValidationException
     */
    public function validate()
    {
        // validate entity
        // ...
        if (sizeof( $this->errors )) {
            throw new \Bluz\Crud\ValidationException('Validation error, please check errors stack');
        }
    }

    /**
     * @return Row
     */
    public function get()
    {
        $primary = array_flip( $this->getTable()->getPrimaryKey() );

        $array = array_intersect_key( $this->getData(), $primary );
        $row = $this->getTable()->findRow( $array );
        return $row;
    }

    /**
     * @return mixed
     */
    public function create()
    {
        $this->validate();
        $row = $this->getTable()->create($this->data);
        return $row->save();
    }

    /**
     * @return mixed
     */
    public function update()
    {
        $this->validate();
        $primary = array_flip( $this->getTable()->getPrimaryKey() );

        $array = array_intersect_key( $this->getData(), $primary );
        $row = $this->getTable()->findRow( $array );

        $row->setFromArray( $this->data );
        return $row->save();
    }

    /**
     * @return mixed
     */
    public function delete()
    {
        $this->validate();
        $primary = array_flip( $this->getTable()->getPrimaryKey() );

        $array = array_intersect_key( $this->getData(), $primary );
        $row = $this->getTable()->findRow( $array );
        return $row->delete();
    }
}
