<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Tests;

use Bluz\Http\RequestMethod;
use Bluz\Tests\FrameworkTestCase;

/**
 * Skeleton TestCase
 *
 * @package Application\Tests
 *
 * @author   Anton Shevchuk
 * @created  04.08.11 20:01
 */

class SkeletonTestCase extends FrameworkTestCase
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
    protected function dispatch($path, array $params = [], $method = RequestMethod::GET, $ajax = false)
    {
        $query = [];

        // this is magic for short signature of function
        // for GET request use $params as `query params`
        // for other requests use $params as `parsed body params`
        if ($method == RequestMethod::GET) {
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

        self::setRequestParams($path, $query, $params, $method, $headers);
        self::getApp()->process();
    }
}
