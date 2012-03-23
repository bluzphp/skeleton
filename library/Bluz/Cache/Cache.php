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
namespace Bluz\Cache;

use Bluz\Package;

/**
 * Cache
 *
 * @category Bluz
 * @package  Cache
 *
 * @method get($key) variable from cache
 * @method add($key, $value, $ttl = 0)
 * @method set($key, $value, $ttl = 0)
 * @method delete($key, $time = 0)
 *
 * @author   Anton Shevchuk
 * @created  12.07.11 12:47
 */
class Cache extends Package
{
    /**
     * @var \Memcached
     */
    protected $_memcached;

	/**
	 * Cache flag
	 * @var boolean
	 */
	protected $_cache = true;

	/**
	 * Servers settings
	 * @var array
	 */
	protected $_servers = array();

    /**
     * init handler
     *
     * @return \Memcached
     */
    public function handler()
    {
        if (!$this->_cache or !sizeof($this->_servers) or !class_exists('Memcached')) {
            return false;
        }

        if (!$this->_memcached) {
            $this->_memcached = new \Memcached();
            $this->_memcached->addServers($this->_servers);
        }
        return $this->_memcached;
    }

    /**
     * setServers
     *
     * @param array $settings
     * @return Cache
     */
    public function setServers(array $settings)
    {
        $this->_servers = $settings;
        return $this;
    }

    /**
     * setCache
     *
     * @param boolean $flag
     * @return Cache
     */
    public function setCache($flag)
    {
        $this->_cache = $flag;
        return $this;
    }

    /**
     * __call
     *
     * @param string $method
     * @param array $params
     * @return mixed
     */
    public function __call($method, $params)
    {
        if (! $handler = $this->handler()) {
            return false;
        }

        if (!method_exists($handler, $method)) {
            throw new CacheException('Method "'.$method.'" is not support by cache handler');
        }

        return call_user_func_array(array($handler, $method), $params);
    }
}