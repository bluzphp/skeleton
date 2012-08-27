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
     * @var Source\AbstractSource
     */
    protected $adapter;

    /**
     * @var Data
     */
    protected $data;

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
     * @var int
     */
    protected $defaultLimit = 25;

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
        $this->processRequest($this->getApplication()->getRequest());
        $this->processSource();
        // initial default helper path
        $this->addHelperPath(dirname(__FILE__) . '/Helper/');
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
     * @param Source\AbstractSource $adapter
     * @return Grid
     */
    public function setAdapter(Source\AbstractSource $adapter)
    {
        $this->adapter = $adapter;
        return $this;
    }

    /**
     * getAdapter
     *
     * @throws GridException
     * @return Source\AbstractSource
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

        $page = $request->getParam($prefix.'page', 1);
        $this->setPage($page);

        $limit = $request->getParam($prefix.'limit', $this->limit);
        $this->setLimit($limit);

        foreach ($this->allowOrders as $column) {
            $order = $request->getParam($prefix.'order-'.$column);
            if ($order) {
                $this->addOrder($column, $order);
            }
        }

        return $this;
    }

    /**
     * processSource
     *
     * @throws GridException
     * @return self
     */
    public function processSource()
    {
        if (null === $this->adapter) {
            throw new GridException("Grid Adapter is not initiated, please change method init() and try again");
        }

        $this->data = $this->getAdapter()->process($this->getSettings());
        
        return $this;
    }
    
    /**
     * getData
     * 
     * @return Data
     */
    public function getData()
    {
        return $this->data;
    }
    
    /**
     * getSettings
     * 
     * @return array
     */
    public function getSettings()
    {
        $settings = array();
        $settings['page'] = $this->page;
        $settings['limit'] = $this->limit;
        $settings['orders'] = $this->orders;
        return $settings;
    }

    /**
     * getParams
     *
     * @param array $rewrite
     * @return array
     */
    public function getParams(array $rewrite = [])
    {
        if ($this->uid) {
            $prefix = $this->uid .'-';
        } else {
            $prefix = '';
        }

        $params = array();
        $params[$prefix.'page'] = (isset($rewrite['page']))?$rewrite['page']:$this->page;

        if (isset($rewrite['limit'])) {
            if ($rewrite['limit'] != $this->defaultLimit) {
                $params[$prefix.'limit'] = ($rewrite['limit']!=$this->limit)?$rewrite['limit']:$this->limit;
            }
        } else {
            if ($this->limit != $this->defaultLimit) {
                $params[$prefix.'limit'] = $this->limit;
            }
        }


        if (isset($rewrite['orders'])) {
            foreach($rewrite['orders'] as $column => $order) {
                $params[$prefix.'order-'.$column] = $order;
            }
        } else {
            foreach($this->orders as $column => $order) {
                $params[$prefix.'order-'.$column] = $order;
            }
        }

        return $params;
    }

    /**
     * setAllowOrders
     *
     * @param array $orders
     * @return Grid
     */
    public function setAllowOrders(array $orders = [])
    {
        $this->allowOrders = $orders;
        return $this;
    }
    
    /**
     * getAllowOrders
     * 
     * @return array
     */
    public function getAllowOrders()
    {
        return $this->allowOrders;
    }

    /**
     * @param        $column
     * @param string $order
     * @throws GridException
     * @return Grid
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
     * @return Grid
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
     * @return Grid
     */
    public function setOrder($column, $order = Grid::ORDER_ASC)
    {
        $this->orders = [];
        $this->addOrder($column, $order);
        return $this;
    }

    /**
     * @param array $orders
     * @return Grid
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
     * getOrders
     * 
     * @return array
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * setPage
     *
     * @param int $page
     * @throws GridException
     * @return Grid
     */
    public function setPage($page = 1)
    {
        if ($page < 1) {
            throw new GridException('Wrong page number, should be greater than zero');
        }
        $this->page = (int) $page;
        return $this;
    }

    /**
     * getPage
     * 
     * @return integer
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * setLimit
     *
     * @param int $limit
     * @throws GridException
     * @return Grid
     */
    public function setLimit($limit)
    {
        if ($limit < 1) {
            throw new GridException('Wrong limit value, should be greater than zero');
        }
        $this->limit = (int) $limit;
        return $this;
    }

    /**
     * getLimit
     * 
     * @return integer
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * setDefaultLimit
     *
     * @param int $limit
     * @throws GridException
     * @return Grid
     */
    public function setDefaultLimit($limit)
    {
        if ($limit < 1) {
            throw new GridException('Wrong default limit value, should be greater than zero');
        }
        $this->setLimit($limit);

        $this->defaultLimit = (int) $limit;
        return $this;
    }

    /**
     * getDefaultLimit
     *
     * @return integer
     */
    public function getDefaultLimit()
    {
        return $this->defaultLimit;
    }
}
