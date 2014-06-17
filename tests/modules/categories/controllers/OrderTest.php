<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Tests\Categories;

use Application\Tests\ControllerTestCase;

/**
 * @package  Application\Tests\Categories
 * @author   Anton Shevchuk
 * @created  17.06.14 10:41
 */
class OrderTest extends ControllerTestCase
{
    /**
     * Dispatch module/controller
     */
    public function testControllerPage()
    {
        $this->setupSuperUserIdentity();

        $this->dispatchRouter('/categories/order/');
        $this->assertModule('categories');
        $this->assertController('order');
        $this->assertOk();
    }
}
