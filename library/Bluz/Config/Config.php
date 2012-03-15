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
namespace Bluz\Config;

use Bluz\Package;

/**
 * Config
 *
 * @category Bluz
 * @package  Config
 *
 * @author   Anton Shevchuk
 * @created  03.03.12 14:03
 */
class Config extends Package
{
    /**
     * @var array
     */
    protected $_config;

    /**
     * load
     *
     * @param string $environment
     * @return bool
     */
    public function load($environment = null)
    {
       $configFile = PATH_APPLICATION .'/configs/application.php';

       if (!is_file($configFile) or !is_readable($configFile)) {
           throw new Exception('Configuration file is not readable');
       }

       // TODO: or need without "once" for multi application
       $this->_config = require $configFile;

       if (null !== $environment) {
           $customConfig = PATH_APPLICATION .'/configs/app.'.$environment.'.php';
           if (is_file($customConfig)) {
               $customConfig = require $customConfig;
               $this->_config = $this->_mergeArrays($this->_config, $customConfig);
           }
       }
    }

    /**
     * return configuration as array
     *
     * @param string|null $section of config
     * @param string|null $subsection of config
     * @return array
     */
    public function get($section = null, $subsection = null)
    {
        if (!$this->_config) {
            throw new Exception("System configuration is missing");
        }

        if (null !== $section && isset($this->_config[$section])) {
            if ($subsection
                && isset($this->_config[$section][$subsection])) {
                return $this->_config[$section][$subsection];
            } else {
                return $this->_config[$section];
            }

        } elseif (null !== $section) {
            return null;
        } else {
            return $this->_config;
        }
    }

    /**
     * @param $array1
     * @param $array2
     * @return array
     */
    protected function _mergeArrays($array1, $array2)
    {
        foreach ($array2 as $key => $value) {
            if (array_key_exists($key, $array1) && is_array($value)) {
                $array1[$key] = $this->_mergeArrays($array1[$key], $array2[$key]);
            } else {
                $array1[$key] = $value;
            }
        }
        return $array1;
    }
}