<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Tests;

use Bluz\Http;
use Bluz\Request\AbstractRequest;
use Bluz\Response\AbstractResponse;

/**
 * Skeleton TestCase
 *
 * @package Application\Tests
 *
 * @author   Anton Shevchuk
 * @created  04.08.11 20:01
 */
class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * Application entity
     *
     * @var \Application\Tests\BootstrapTest
     */
    private $app;

    /**
     * Setup TestCase
     */
    protected function setUp()
    {
        $this->getApp();
    }

    /**
     * Tear Down
     *
     * @return void
     */
    protected function tearDown()
    {
        $this->resetApp();
    }

    /**
     * Get Application instance
     *
     * @return BootstrapTest
     */
    protected function getApp()
    {
        if (!$this->app) {
            $env = getenv('BLUZ_ENV') ?: 'testing';

            $this->app = BootstrapTest::getInstance();
            $this->app->init($env);
        }

        return $this->app;
    }

    /**
     * Reset layout and Request
     */
    private function resetApp()
    {
        if ($this->app) {
            $this->app->resetLayout();
            $this->app->getAuth()->clearIdentity();
            $this->app->setRequest(new Http\Request());
            $this->app->setResponse(new Http\Response());
            $this->app->useJson(false);
            $this->app->useLayout(true);
            $this->app->getMessages()->popAll();
        }
    }

    /**
     * prepareRequest
     *
     * @param string $uri in format "module/controller"
     * @param array $params of request
     * @param string $method HTTP
     * @param bool $ajax
     * @return Http\Request
     */
    private function prepareRequest($uri, array $params = null, $method = Http\Request::METHOD_GET, $ajax = false)
    {
        $request = $this->app->getRequest();
        $request->setRequestUri($uri);
        $request->setOptions($this->app->getConfigData('request'));
        $request->setMethod($method);

        // process $_GET params
        if ($query = stristr($uri, '?')) {
            $query = substr($query, 1); // remove `?` sign
            parse_str($query, $_GET);   // fill $_GET
        }

        // process custom params
        if ($params) {
            $request->setParams($params);
        }

        $this->app->setRequest($request);

        if ($ajax) {
            $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
            $this->app->useLayout(false);
        }
    }

    /**
     * dispatch URI
     *
     * @param string $uri in format "module/controller"
     * @param array $params of request
     * @param string $method HTTP
     * @param bool $ajax
     * @return AbstractResponse
     */
    protected function dispatchUri($uri, array $params = null, $method = Http\Request::METHOD_GET, $ajax = false)
    {
        $request = $this->prepareRequest($uri, $params, $method, $ajax);

        $uri = trim($uri, '/ ');
        list($module, $controller) = explode('/', $uri);

        // set default controller
        if (!$controller) {
            $controller = $request->getController();
        }

        $this->app->getRequest()
            ->setModule($module)
            ->setController($controller)
            ->setRequestUri($uri);

        return $this->app->process();
    }

    /**
     * dispatch URI over Router
     *
     * @param string $uri in format "module/controller"
     * @param array $params of request
     * @param string $method HTTP
     * @param bool $ajax
     * @return AbstractResponse
     */
    protected function dispatchRouter($uri, array $params = null, $method = Http\Request::METHOD_GET, $ajax = false)
    {
        $this->prepareRequest($uri, $params, $method, $ajax);

        $router = $this->app->getRouter();

        $router->process();

        return $this->app->process();
    }

    /**
     * dispatch Request
     *
     * @param AbstractRequest $request
     *
     * @return AbstractResponse
     */
    protected function dispatchRequest($request)
    {
        $this->app->setRequest($request);
        return $this->app->process();
    }
}
