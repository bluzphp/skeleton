<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Tests\Test;

use Application\Tests\ControllerTestCase;

/**
 * @package  Application\Tests\Test
 * @author   Anton Shevchuk
 * @created  19.05.14 12:33
 */
class ClosureTest extends ControllerTestCase
{
    /**
     * Dispatch module/controller
     */
    public function testControllerPage()
    {
        $this->getApp()->useLayout(false);
        $this->dispatchRouter('/test/closure/');
        $this->assertOk();

        $closure = $this->getApp()->getResponse()->getBody();

        $this->assertInstanceOf('\Closure', $closure);
        $this->expectOutputRegex('/Closure is back/');
        $closure();
    }
}
