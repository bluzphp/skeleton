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
use Bluz\Http\Request;

/**
 * @package  Application\Tests\Test
 * @author   Anton Shevchuk
 * @created  19.05.14 12:33
 */
class BootstrapModuleTest extends ControllerTestCase
{
    /**
     * Dispatch module/controller
     */
    public function testControllerPage()
    {
        $this->getApp()->useLayout(false);
        $this->dispatchRouter('/test/bootstrap/');
        $this->assertOk();
        $this->assertResponseVariable('result', 4);
    }
}
