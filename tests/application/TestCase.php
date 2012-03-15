<?php
/**
 * ControllerTestCase
 *
 * @category Tests
 * @package  Application
 *
 * @author   Anton Shevchuk
 * @created  04.08.11 20:01
 */
namespace Application;

class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * Application entity
     *
     * @var Application
     */
    protected $_app;

    /**
     * Setup TestCase
     */
    protected function setUp()
    {
        $this->_app = new \BootstrapTest();
        $this->_app->init(APPLICATION_ENV);
    }
}
