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
namespace Bluz\EventManager;

/**
 * Representation of an event
 *
 * @category Bluz
 * @package  EventManager
 */
class Event
{
    /**
     * @var string Event name
     */
    protected $_name;

    /**
     * @var string|object The event target
     */
    protected $_target;

    /**
     * @var array|object The event parameters
     */
    protected $_params = array();

    /**
     * Constructor
     *
     * Accept a target and its parameters.
     *
     * @param  string        $name Event name
     * @param  string|object $target
     * @param  array|object  $params
     * @return \Bluz\EventManager\Event
     */
    public function __construct($name, $target = null, $params = null)
    {
        $this->setName($name);

        if (null !== $target) {
            $this->setTarget($target);
        }

        if (null !== $params) {
            $this->setParams($params);
        }
    }

    /**
     * Get event name
     * 
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Get the event target
     *
     * This may be either an object, or the name of a static method.
     * 
     * @return string|object
     */
    public function getTarget()
    {
        return $this->_target;
    }

    /**
     * Overwrites parameters
     *
     * @param  array|object $params
     * @return Event
     */
    public function setParams($params)
    {
        if (!is_array($params) && !is_object($params)) {
            throw new EventException(sprintf(
                'Event parameters must be an array or object; received "%s"',
                (is_object($params) ? get_class($params) : gettype($params))
            ));
        }

        $this->_params = $params;
        return $this;
    }

    /**
     * Get all parameters
     * 
     * @return array|object
     */
    public function getParams()
    {
        return $this->_params;
    }

    /**
     * Get an individual parameter
     *
     * If the parameter does not exist, the $default value will be returned.
     * 
     * @param  string|int $name 
     * @param  mixed $default 
     * @return mixed
     */
    public function getParam($name, $default = null)
    {
        // Check in params that are arrays or implement array access
        if (is_array($this->_params)) {
            if (!isset($this->_params[$name])) {
                return $default;
            }
            return $this->_params[$name];
        }

        // Check in normal objects
        if (!isset($this->_params->{$name})) {
            return $default;
        }
        return $this->_params->{$name};
    }

    /**
     * Set the event name
     * 
     * @param  string $name 
     * @return Event
     */
    public function setName($name)
    {
        $this->_name = (string) $name;
        return $this;
    }

    /**
     * Set the event target/context
     * 
     * @param  null|string|object $target 
     * @return Event
     */
    public function setTarget($target)
    {
        $this->_target = $target;
        return $this;
    }

    /**
     * Set an individual parameter to a value
     * 
     * @param  string|int $name 
     * @param  mixed $value 
     * @return Event
     */
    public function setParam($name, $value)
    {
        if (is_array($this->_params)) {
            // Arrays or objects implementing array access
            $this->_params[$name] = $value;
        } else {
            // Objects
            $this->_params->{$name} = $value;
        }
        return $this;
    }
}
