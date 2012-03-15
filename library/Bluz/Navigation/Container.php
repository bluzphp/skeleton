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
 * Navigation container class.
 *
 * @category Bluz
 * @package  Navigation
 * 
 * @author Eugene Zabolotniy <realbaziak@gmail.com>
 */
class Container implements \RecursiveIterator
{
    /**
     *
     * @var array of pages.
     */
    private $_children = array();

    /**
     * Container constructor.
     *
     * @param array $children
     */
    public function __construct(array $children = null)
    {
        if (null !== $children) {
            $this->setChildren($children);
        }
    }

    /**
     * Adds a child page.
     *
     * @param array|Page $page
     * @return Container
     */
    public function addChild($page)
    {
        if ($page instanceof self) {
            if ($this->hasChild($page)) {
                throw new \Exception('Child already present.');
            }
        } elseif (is_array($page)) {
            $page = new Page($page);
        } else {
            throw new \Exception(
                'Page parameter should be an instanse of Page class or an array,'
                 . ' but ' . gettype($page) . ' given.'
            );
        }

        

        $this->_children[] = $page;

        return $this;
    }

    /**
     * Check if has a child.
     *
     * @param Page $page
     * @return boolean
     */
    public function hasChild(Page $page)
    {
        foreach ($this->_children as $child) {
            if ($child === $page) {
                return true;
            }
        }

        return false;
    }

    /**
     * Removes a child.
     *
     * @param Page $page
     * @return Container
     */
    public function removeChild(Page $page)
    {
        foreach ($this->_children as $key => $child) {
            if ($child === $page) {
                unset($this->_children[$key]);
                break;
            }
        }

        return $this;
    }

    /**
     * Gets children.
     *
     * @return array
     */
    public function getChildren()
    {
        return $this->_children;
    }

    /**
     * Sets children.
     *
     * @param array $children
     * @return Container
     */
    public function setChildren(array $children)
    {
        return $this->removeChildren()->addChildren($children);
    }

    /**
     * Adds children.
     *
     * @param array $children
     * @return Container
     */
    public function addChildren(array $children)
    {
        foreach ($children as $child) {
            $this->addChild($child);
        }

        return $this;
    }

    /**
     * Check if has any children.
     *
     * @return boolean
     */
    public function hasChildren()
    {
        return (boolean) count($this->_children);
    }

    /**
     * Removes all children.
     *
     * @return Container
     */
    public function removeChildren()
    {
        $this->_children = array();

        return $this;
    }

    /**
     * Returns current page or null.
     *
     * @return Page|null
     */
    public function current()
    {
        return current($this->_children);
    }

    /**
     * Gets current key.
     *
     * @return string
     */
    public function key()
    {
        return key($this->_children);
    }

    /**
     * Get next page.
     *
     * @return type
     */
    public function next()
    {
        return next($this->_children);
    }

    /**
     * Rewinds page list.
     */
    public function rewind()
    {
        reset($this->_children);
    }

    /**
     * Checks if current position is valid.
     *
     * @return boolean
     */
    public function valid()
    {
        return key($this->_children) !== NULL;
    }

}
