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
namespace Application\Test;


/**
 * Pages Grid
 *
 * @category Application
 * @package  Pages
 */
class ArrayGrid extends \Bluz\Grid\Grid
{
    /**
     * init
     * 
     * @return self
     */
    public function init()
    {
         // Array
         $adapter = new \Bluz\Grid\Source\ArraySource();
         $adapter->setSource([
             ['id'=>1, 'name'=>'Foo', 'email'=>'a@bc.com', 'status'=>'active'],
             ['id'=>2, 'name'=>'Bar', 'email'=>'d@ef.com', 'status'=>'active'],
             ['id'=>3, 'name'=>'Foo 2', 'email'=>'m@ef.com', 'status'=>'disable'],
             ['id'=>4, 'name'=>'Foo 3', 'email'=>'j@ef.com', 'status'=>'disable'],
             ['id'=>5, 'name'=>'Foo 4', 'email'=>'g@ef.com', 'status'=>'disable'],
             ['id'=>6, 'name'=>'Foo 5', 'email'=>'r@ef.com', 'status'=>'disable'],
             ['id'=>7, 'name'=>'Foo 6', 'email'=>'m@ef.com', 'status'=>'disable'],
             ['id'=>8, 'name'=>'Foo 7', 'email'=>'n@ef.com', 'status'=>'disable'],
             ['id'=>9, 'name'=>'Foo 8', 'email'=>'w@ef.com', 'status'=>'disable'],
             ['id'=>10, 'name'=>'Foo 9', 'email'=>'l@ef.com', 'status'=>'disable'],
         ]);

         $this->setAdapter($adapter);
         $this->setDefaultLimit(3);
         $this->setAllowOrders(['name', 'email', 'id']);
         $this->setAllowFilters(['status', 'id']);

         return $this;
    }
}
