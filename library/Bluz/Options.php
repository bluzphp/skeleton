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
 * Options
 *
 * @category Bluz
 * @package  Options
 *
 * <code>
 * use Bluz\Options as Options;
 * class Foo
 * {
 *   protected $_bar = '';
 *   protected $_baz = '';
 *
 *   public function __construct($options = null)
 *   {
 *       Options::setConstructorOptions($this, $options);
 *   }
 *
 *   public function setOptions(array $options)
 *   {
 *       Options::setOptions($this, $options);
 *   }
 *
 *   public function setBar($value)
 *   {
 *       $this->_bar = $value;
 *   }
 *
 *   public function setBaz($value)
 *   {
 *       $this->_baz = $value;
 *   }
 * }
 * </code>
 *
 * @see http://framework.zend.com/wiki/display/ZFDEV2/Zend+Framework+2.0+Roadmap
 * @author   Anton Shevchuk
 * @created  08.07.11 12:17
 */
class Options
{
    /**
     * @static
     * @param  $object
     * @param array $options
     * @return void
     */
    public static function setOptions($object, array $options)
    {
        if (!is_object($object)) {
            return;
        }
        foreach ($options as $key => $value) {
            $method = 'set' . self::_normalizeKey($key);
            if (method_exists($object, $method)) {
                $object->$method($value);
            }
        }
    }

    /**
     * @static
     * @param  $object
     * @param  $options
     * @return void
     */
    public static function setConstructorOptions($object, $options)
    {
        if (is_array($options)) {
            self::setOptions($object, $options);
        }
    }

    /**
     * @static
     * @param  $key
     * @return mixed
     */
    protected static function _normalizeKey($key)
    {
        $option = str_replace('_', ' ', strtolower($key));
        $option = str_replace(' ', '', ucwords($option));
        return $option;
    }
}