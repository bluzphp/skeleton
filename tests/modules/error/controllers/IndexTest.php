<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Tests\Error;

use Application\Tests\ControllerTestCase;
use Bluz\Proxy\Response;

/**
 * @package Application\Tests\Error
 * @author   Anton Shevchuk
 * @created  04.08.11 19:52
 */
class IndexTest extends ControllerTestCase
{
    /**
     * test for Page not found
     *
     * @return void
     */
    public function testNotFoundError404()
    {
        $this->dispatch(uniqid('module'). '/'. uniqid('controller'));

        $this->assertModule('error');
        $this->assertController('index');
        $this->assertEquals(Response::getStatusCode(), 404);
    }
}
