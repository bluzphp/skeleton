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
namespace Bluz;

/**
 * Package
 *
 * @category Bluz
 * @package  Package
 *
 * @author   Anton Shevchuk
 * @created  12.07.11 16:15
 */
class Package
{
    /**
     * @var Application
     */
    protected $_application;

    /**
     * Constructor
     *
     * @param array $options
     * @access  public
     */
    public function __construct($options = null)
    {
        Options::setConstructorOptions($this, $options);
    }

    /**
     * Setup options
     * @param array $options
     */
    public function setOptions(array $options)
    {
        Options::setOptions($this, $options);
    }

    /**
     * setApplication
     *
     * @param Application $application
     * @return Package
     */
    public function setApplication(Application $application)
    {
        $this->_application = $application;
    }

    /**
     * getApplication
     *
     * @return Application
     */
    public function getApplication()
    {
        if (!$this->_application) {
            throw new Exception('Application link not found for "'.get_called_class().'" class. Please use method "setApplication()" for initial it');
        }
        return $this->_application;
    }
}
