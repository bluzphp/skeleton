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
namespace Bluz\Grid;

use Bluz\Application;

/**
 * Grid
 *
 * @category Bluz
 * @package  Grid
 *
 * @author   Anton Shevchuk
 * @created  15.08.12 11:52
 */
abstract class Grid
{
    use \Bluz\Package;
    use \Bluz\Helper;

    /**
     * @var AbstractAdapter
     */
    protected $adapter;

    /**
     * Unique identification of grid
     *
     * @var string
     */
    protected $uid;

    const ORDER_ASC = 'asc';
    const ORDER_DESC = 'desc';

    /**
     * Start from 1!
     *
     * @var int
     */
    protected $page = 1;

    /**
     * @var int
     */
    protected $limit = 25;

    /**
     * <code>
     * [
     *     'first' => 'ASC',
     *     'last' => 'ASC'
     * ]
     * </code>
     * @var array
     */
    protected $orders = array();

    /**
     * <code>
     * ['first', 'last', 'email']
     * </code>
     * @var array
     */
    protected $allowOrders = array();
    /**
     * __construct
     *
     * @return Grid
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     * init
     *
     * @return Grid
     */
    abstract public function init();

    /**
     * setAdapter
     *
     * @param AbstractAdapter $adapter
     * @return Grid
     */
    public function setAdapter(AbstractAdapter $adapter)
    {
        $this->adapter = $adapter;
        return $this;
    }

    /**
     * getAdapter
     *
     * @throws GridException
     * @return AbstractAdapter
     */
    public function getAdapter()
    {
        if (null == $this->adapter) {
            throw new GridException('Grid adapter is not initialized');
        }
        return $this->adapter;
    }

    /**
     * process request
     *
     * <code>
     * // example of request url
     * // http://domain.com/pages/grid/
     * // http://domain.com/pages/grid/page/2/
     * // http://domain.com/pages/grid/page/2/order-alias/desc/
     * // http://domain.com/pages/grid/page/2/order-created/desc/order-alias/asc/
     *
     * // with prefix for support more than one grid on page
     * // http://domain.com/users/grid/users-page/2/users-order-created/desc/
     * // http://domain.com/users/grid/users-page/2/users-filter-status/active/
     *
     * // hash support
     * // http://domain.com/pages/grid/#/page/2/order-created/desc/order-alias/asc/
     *
     * </code>
     *
     * @param \Bluz\Request\AbstractRequest $request
     * @return Grid
     */
    public function processRequest(\Bluz\Request\AbstractRequest $request)
    {
        if ($this->uid) {
            $prefix = $this->uid .'-';
        } else {
            $prefix = '';
        }

        $request->getParam($prefix.'page', 1);

        foreach ($this->allowOrders as $column) {
            $order = $request->getParam($prefix.'order-'.$column);
            if ($order) {
                $this->addOrder($column, $order);
            }
        }




        return $this;
    }


    /**
     * @param        $column
     * @param string $order
     * @return AbstractAdapter
     * @throws GridException
     */
    public function addOrder($column, $order = Grid::ORDER_ASC)
    {
        if (!in_array($column, $this->allowOrders)) {
            throw new GridException('Wrong column order');
        }

        if (strtolower($order) != Grid::ORDER_ASC
            && strtolower($order) != Grid::ORDER_DESC) {
            throw new GridException('Order for column "'.$column.'" is incorrect');
        }

        $this->orders[$column] = $order;

        return $this;
    }

    /**
     * @param array $orders
     * @return AbstractAdapter
     */
    public function addOrders(array $orders)
    {
        foreach ($orders as $column => $order) {
            $this->addOrder($column, $order);
        }
        return $this;
    }

    /**
     * @param        $column
     * @param string $order
     * @return AbstractAdapter
     */
    public function setOrder($column, $order = Grid::ORDER_ASC)
    {
        $this->orders = [];
        $this->addOrder($column, $order);
        return $this;
    }

    /**
     * @param array $orders
     * @return AbstractAdapter
     */
    public function setOrders(array $orders)
    {
        $this->orders = [];
        foreach ($orders as $column => $order) {
            $this->addOrder($column, $order);
        }
        return $this;
    }

    /**
     * setLimit
     *
     * @param $limit
     * @throws GridException
     * @return Grid
     */
    function setLimit($limit)
    {
        if ($limit < 1) {
            throw new GridException('Wrong limit');
        }
        $this->limit = $limit;
        return $this;
    }
}
