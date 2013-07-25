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
namespace Application\Test;


/**
 * Test Grid based on SQL
 *
 * @category Application
 * @package  Test
 */
class SqlGrid extends \Bluz\Grid\Grid
{
    protected $uid = 'sql';

    /**
     * init
     * 
     * @return self
     */
    public function init()
    {
         // Array
         $adapter = new \Bluz\Grid\Source\SqlSource();
         $adapter->setSource('SELECT * FROM test');

         $this->setAdapter($adapter);
         $this->setDefaultLimit(15);
         $this->setAllowOrders(['name', 'id', 'status']);
         $this->setAllowFilters(['status', 'id']);

         return $this;
    }
}
