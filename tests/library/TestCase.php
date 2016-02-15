<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Tests;

use Bluz\Proxy;
use Bluz\Proxy\Request;
use Bluz\Tests\TestCase as BluzTest;

/**
 * Skeleton TestCase
 *
 * @package Application\Tests
 *
 * @author   Anton Shevchuk
 * @created  04.08.11 20:01
 */

class TestCase extends BluzTest
{
    /**
     * Setup TestCase
     */
    protected function setUp()
    {
        self::getApp();
    }

    /**
     * Tear Down
     */
    protected function tearDown()
    {
        self::resetApp();
        self::resetGlobals();
    }

    /**
     * dispatch URI over Router
     *
     * @param string $path in format "module/controller"
     * @param array $params of request
     * @param string $method HTTP
     * @param bool $ajax
     * @return void
     */
    protected function dispatch($path, array $params = null, $method = Request::METHOD_GET, $ajax = false)
    {
        $query = [];

        // this is magic for short signature of function
        // for GET request use $params as `query params`
        // for other requests use $params as `parsed body params`
        if ($method == Request::METHOD_GET) {
            $query = $params;
            $params = [];
        }

        if ($ajax) {
            $headers = [
                'Accept' => 'application/json',
                'X-Requested-With' => 'XMLHttpRequest',
            ];
        } else {
            $headers = [
                'Accept' => 'text/html',
            ];
        }

        $this->setRequestParams($path, $query, $params, $method, $headers);
        $this->getApp()->process();
    }
}
