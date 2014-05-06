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
        $response = $this->dispatchUri(uniqid('module'). '/'. uniqid('controller'));

        $this->assertModule('error');
        $this->assertController('index');
        $this->assertEquals($response->getCode(), 404);
    }
}
