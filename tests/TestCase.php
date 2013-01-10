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
use Bluz\Request;
class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * Application entity
     *
     * @var \Application\Bootstrap
     */
    protected $app;

    /**
     * Setup TestCase
     */
    protected function setUp()
    {
        $this->app = BootstrapTest::getInstance();
        $this->app->init('testing');

        $this->_reset();
    }

    private function _reset()
    {
        $this->app->resetLayout();

        $this->app->setRequest(new \Bluz\Request\HttpRequest());
    }

    /*
     * dispatch URI
     * @param array $params
     * @param string $uri
     *
     * */
    protected  function dispatchUri($uri,  array $params = null)
    {
        $this->app->setRequest(new Request\HttpRequest());
        $this->app->getRequest()->setOptions($this->app->getConfigData('request'));
        $this->app->getRequest()->setMethod('GET');
        $this->app->getRequest()->setRequestUri($uri);
        if ($params) {
            $this->app->getRequest()->setParams($params);
        }
        $this->app->process();

    }

    /*
     * dispatch Request
     * @param object $request
     *
     * */
    protected function dispatchRequest($request)
    {
        $this->app->setRequest($request);
        $this->app->process();
    }
}
