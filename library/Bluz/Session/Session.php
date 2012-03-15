<?php

/**
 * Copyright (c) 2011 by Bluz PHP Team
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
namespace Bluz\Session;

use Bluz\Package;

/**
 * Session
 *
 * @category Bluz
 * @package  Session
 *
 * @author   Anton Shevchuk
 * @created  11.07.11 19:19
 *
 * @property mixed _messages
 * @property mixed identity
 */
class Session extends Package
{
    /**
     * Session store instance
     * @var Store\AbstractStore
     */
    protected $_store = null;

    /**
     * Session store name
     * @var string
     */
    protected $_storeName = 'session';

    /**
     * Session store options
     * @var string
     */
    protected $_storeOptions = array();

    /**
     * setStore
     *
     * @param string $store description
     * @return Session
     */
    public function setStore($store)
    {
        $this->_storeName = $store;
        return $this;
    }

    /**
     * setStore
     *
     * @param array $options
     * @return Session
     */
    public function setOptions($options = array())
    {
        $this->_storeOptions = $options;
        return $this;
    }

    /**
     * buildStore
     *
     * @return Session
     */
    public function start()
    {
        if (!$this->_store) {
            // switch statement for $store
            switch ($this->_storeName) {
                case 'array':
                    $this->_store = new Store\ArrayStore($this->_storeOptions);
                    break;
                case 'session':
                default:
                    $this->_store = new Store\SessionStore($this->_storeOptions);
                    break;
            }
        }
        return $this->_store->start();
    }

    /**
     * getStore
     *
     * @return Store\AbstractStore
     */
    public function getStore()
    {
        if (!$this->_store) {
            throw new SessionException("Session store is not configured");
        }

        return $this->_store;
    }

    /**
     * __set
     *
     * @param string $key
     * @param mixed $value
     * @return boolean
     */
    public function __set($key, $value)
    {
        return $this->getStore()->set($key, $value);
    }

    /**
     * __get
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->getStore()->get($key);
    }
}
