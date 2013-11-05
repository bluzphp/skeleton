<?php
/**
 * Copyright (c) 2013 by Bluz PHP Team
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
namespace Application\Options;

/**
 * Table
 *
 * @category Application
 * @package  Options
 */
class Table extends \Bluz\Db\Table
{
    /**
     * Default namespace for all keys
     */
    const NAMESPACE_DEFAULT = 'default';

    /**
     * Table
     *
     * @var string
     */
    protected $table = 'options';

    /**
     * Primary key(s)
     * @var array
     */
    protected $primary = array('namespace', 'key');

    /**
     * Get option value for use it from any application place
     *
     * @param string $key
     * @param string $namespace
     * @return mixed
     */
    public static function get($key, $namespace = self::NAMESPACE_DEFAULT)
    {
        /**
         * @var \Application\Options\Row $row
         */
        if ($row = self::findRowWhere(['key' => $key, 'namespace' => $namespace])) {
            return $row->value;
        }
        return null;
    }

    /**
     * Set option value for use it from any application place
     *
     * @param string $key
     * @param mixed $value
     * @param string $namespace
     * @return mixed
     */
    public static function set($key, $value, $namespace = self::NAMESPACE_DEFAULT)
    {
        /**
         * @var \Application\Options\Row $row
         */
        $row = self::findRowWhere(['key' => $key, 'namespace' => $namespace]);
        if (!$row) {
            $row = self::create();
            $row->key = $key;
            $row->value = $value;
            $row->namespace = $namespace;
        }
        $row->value = $value;
        return $row->save();
    }

    /**
     * Remove option
     *
     * @param string $key
     * @param string $namespace
     * @return boolean
     */
    public static function remove($key, $namespace = self::NAMESPACE_DEFAULT)
    {
        return self::delete(['key' => $key, 'namespace' => $namespace]);
    }
}
