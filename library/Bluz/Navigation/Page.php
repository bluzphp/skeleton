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

namespace Bluz\Navigation;

/**
 * Navigation page class.
 *
 * @category Bluz
 * @package  Navigation
 * 
 * @author Eugene Zabolotniy <realbaziak@gmail.com>
 */
class Page extends Container
{
    /**
     * Properties map.
     *
     * @var array
     */
    protected $_properties = array();

    /**
     * Resource constructor.
     *
     * @param array|\Bluz\Navigation\type $properties
     */
    public function __construct(array $properties)
    {
        foreach ($properties as $name => $value) {

            $this->$name = $value;
        }
    }

    /**
     * Checks if a property is set.
     *
     * @param string $name
     * @return boolean
     */
    public function __isset($name)
    {
        return $this->hasProperty($name) && $this->_read($name) !== null;
    }

    /**
     * Unsets a property.
     *
     * @param string $name
     */
    public function __unset($name)
    {
         $this->removeProperty($name);
    }

    /**
     * Gets a property.
     *
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->getProperty($name);
    }

    /**
     * Sets a property.
     *
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        $this->setProperty($name, $value);
    }

    /**
     * Checks if page has property.
     *
     * @param string $name
     * @return type
     */
    public function hasProperty($name)
    {
        return array_key_exists($name, $this->_properties);
    }

    /**
     * Gets property.
     *
     * @param string $name
     * @return mixed
     */
    public function getProperty($name)
    {
        $getterName = 'get' . strtoupper($name);

        if (method_exists($this, $getterName)) {
            return $this->$getterName();
        }

        return $this->hasProperty($name) ? $this->_properties[$name] : null;
    }

    /**
     * Sets a property.
     *
     * @param string $name
     * @param mixed $value
     * @return Page
     */
    public function setProperty($name, $value)
    {
        $setterName = 'set' . strtoupper($name);

        if (method_exists($this, $setterName)) {
            $this->$setterName($value);
        } else {
            $this->_properties[$name] = $value;
        }

        return $this;
    }

    /**
     * Remove property.
     *
     * @param string $name
     * @return Page
     */
    public function removeProperty($name)
    {
        if ($this->hasProperty($name)) {
             unset($this->_properties[$name]);
        }

        return $this;
    }
}
