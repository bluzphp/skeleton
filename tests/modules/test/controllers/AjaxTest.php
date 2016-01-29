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
use Bluz\Proxy\Request;

/**
 * @package  Application\Tests\Test
 * @author   Anton Shevchuk
 * @created  19.05.14 12:33
 */
class AjaxTest extends ControllerTestCase
{
    /**
     * Dispatch module/controller
     */
    public function testAjax()
    {
        $this->dispatch('/test/ajax/', [], Request::METHOD_POST, true);
        $this->assertOk();
        $this->assertNoticeMessage();
        $this->assertResponseVariable('foo', 'bar');
    }

    /**
     * Dispatch module/controller
     */
    public function testAjaxWithParams()
    {
        $this->dispatch('/test/ajax/', ['messages'=>1], Request::METHOD_POST, true);
        $this->assertOk();
        $this->assertErrorMessage();
        $this->assertNoticeMessage();
        $this->assertSuccessMessage();
        $this->assertResponseVariable('foo', 'bar');
        $this->assertResponseVariable('baz', 'qux');
    }
}
