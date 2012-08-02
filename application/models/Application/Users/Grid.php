<?php

/**
 * Grid
 *
 * @category Application
 * @package  Users
 *
 * @author   AntonShevchuk
 * @created  02.08.12 15:23
 */
namespace Application\Users;
class Grid extends \Bluz\Grid\Grid
{
    /**
     * @var int
     */
    protected $total = 0;

    /**
     * Limit per page
     * @var int
     */
    protected $limit = 50;

    /**
     * Page
     * @var int
     */
    protected $page = 0;

    /**
     * Orders
     * @var array
     */
    protected $orders = array(
        'id'=>'desc'
    );

    /**
     * @var array
     */
    protected $filters = array();

    /**
     * Allow orders
     * @var array
     */
    protected $ordersAllow = array(
        'id'
    );

    /**
     * setPage
     *
     * @return Grid
     */
    public function setPage($page = 0)
    {
        $this->page = $page;
        return $this;
    }

    /**
     * setOrder
     *
     * @return Grid
     */
    public function setOrders($orders = array())
    {
        foreach ($orders as $order) {
            $this->setOrder($order);
        }
        return $this;
    }


    /**
     * getSource
     *
     * @return GridAdapter
     */
    protected function getSource()
    {
        // init adapter
        // SQL, array or other
        $adapter = new GridTableAdapter();
        $adapter->setSource(new \Application\Users\Table());
        return $adapter;
    }
}
