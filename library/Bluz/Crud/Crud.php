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
namespace Bluz\Crud;

use Bluz\Application;
use Bluz\Request\AbstractRequest;

/**
 * Crud
 *
 * @category Bluz
 * @package  Crud
 *
 * @author   AntonShevchuk
 * @created  15.08.12 15:37
 */
class Crud
{
    use \Bluz\Package;

    /**
     * @var string
     */
    protected $method = AbstractRequest::METHOD_GET;

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
        $this->data = $request->getParams();


        // rewrite REST with "method" param
        $this->method = $request->getParam('_method', $request->getMethod());

        // switch by method
        switch ($this->method) {
            case AbstractRequest::METHOD_POST:
                $this->result = $this->create();
                break;
            case AbstractRequest::METHOD_PUT:
                $this->result = $this->update();
                break;
            case AbstractRequest::METHOD_DELETE:
                $this->result = $this->delete();
                break;
            case AbstractRequest::METHOD_GET:
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
     * getMethod
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
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
     * @throws CrudException
     * @return \Bluz\Db\Row|null
     */
    public function get()
    {
        $primary = array_flip( $this->getTable()->getPrimaryKey() );
        $array = array_intersect_key( $this->getData(), $primary );

        if (!sizeof($array)) {
            return null;
        }

        $row = $this->getTable()->findRow( $array );
        return $row;
    }

    /**
     * @return boolean
     */
    public function create()
    {
        $this->validate();
        $row = $this->getTable()->create($this->data);
        return $row->save();
    }

    /**
     * @throws CrudException
     * @return boolean
     */
    public function update()
    {
        $this->validate();
        $primary = array_flip( $this->getTable()->getPrimaryKey() );

        $array = array_intersect_key( $this->getData(), $primary );
        $row = $this->getTable()->findRow( $array );

        if (!$row) {
            throw new CrudException("Record not found");
        }

        $row->setFromArray( $this->data );
        return $row->save();
    }

    /**
     * @throws CrudException
     * @return boolean
     */
    public function delete()
    {
        $this->validate();
        $primary = array_flip( $this->getTable()->getPrimaryKey() );

        $array = array_intersect_key( $this->getData(), $primary );
        $row = $this->getTable()->findRow( $array );
        if (!$row) {
            throw new CrudException("Record not found");
        }
        return $row->delete();
    }

}
